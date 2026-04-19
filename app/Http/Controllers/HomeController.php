<?php

namespace App\Http\Controllers;

use App\Models\Block;
use App\Models\Deceased;
use App\Models\Facility;
use App\Models\GravePlot;
use App\Models\LegacyUser;
use App\Models\SystemSetting;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(Request $request): View
    {
        $data = $this->buildDashboardData();
        $facilityData = $this->buildFacilityData();
        $setting = $this->resolveSystemSetting();

        return view('home', [
            ...$data,
            ...$facilityData,
            'setting' => $setting,
            'authUser' => $request->session()->get('auth_user'),
        ]);
    }

    public function chatbot(Request $request): View
    {
        $setting = $this->resolveSystemSetting();

        return view('chatbot', [
            'setting' => $setting,
            'authUser' => $request->session()->get('auth_user'),
        ]);
    }

    public function chatbotAsk(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'message' => ['required', 'string', 'max:500'],
        ]);

        $message = trim((string) $validated['message']);
        $answer = $this->resolveChatbotAnswer($message);

        return response()->json([
            'answer' => $answer,
        ]);
    }

    public function dashboard(Request $request): View|RedirectResponse
    {
        if (! $request->session()->has('auth_user')) {
            return redirect('/login')
                ->with('status', 'Silakan login terlebih dahulu.');
        }

        $data = $this->buildDashboardData();
        $setting = $this->resolveSystemSetting();
        $facilityData = $this->buildFacilityData();

        return view('dashboard', [
            ...$data,
            ...$facilityData,
            'setting' => $setting,
            'authUser' => $request->session()->get('auth_user'),
        ]);
    }

    public function dataBlok(Request $request): View|RedirectResponse
    {
        if (! $request->session()->has('auth_user')) {
            return redirect('/login')
                ->with('status', 'Silakan login terlebih dahulu.');
        }
        if (! $this->canAccessMenu($request, 'data-blok')) {
            return redirect()->route('dashboard')
                ->with('status', 'Hak akses ke menu Data Blok tidak tersedia.');
        }

        $blockColumns = ['blockid', 'block_name', 'description', 'map_color'];
        if (Schema::hasColumn('blocks', 'max_plots')) {
            $blockColumns[] = 'max_plots';
        }
        if (Schema::hasColumn('blocks', 'map_x')) {
            $blockColumns[] = 'map_x';
        }
        if (Schema::hasColumn('blocks', 'map_y')) {
            $blockColumns[] = 'map_y';
        }

        $blocks = Block::query()
            ->select($blockColumns)
            ->withCount([
                'gravePlots as total_plots',
                'gravePlots as occupied_plots' => fn ($query) => $query->where('status', 'occupied'),
                'gravePlots as empty_plots' => fn ($query) => $query->where('status', 'empty'),
                'gravePlots as reserved_plots' => fn ($query) => $query->where('status', 'reserved'),
                'gravePlots as maintenance_plots' => fn ($query) => $query->where('status', 'maintenance'),
            ])
            ->orderBy('blockid')
            ->get();

        $dashboardData = $this->buildDashboardData();
        $pickerBlockMaps = collect($dashboardData['blockMaps'] ?? [])
            ->mapWithKeys(fn (array $item) => [
                (int) ($item['id'] ?? 0) => [
                    'x' => (int) ($item['x'] ?? 0),
                    'y' => (int) ($item['y'] ?? 0),
                    'width' => (int) ($item['width'] ?? 240),
                    'height' => (int) ($item['height'] ?? 180),
                ],
            ]);

        return view('data-blok', [
            'authUser' => $request->session()->get('auth_user'),
            'blocks' => $blocks,
            'pickerBlockMaps' => $pickerBlockMaps,
        ]);
    }

    public function dataPlot(Request $request): View|RedirectResponse
    {
        if (! $request->session()->has('auth_user')) {
            return redirect('/login')
                ->with('status', 'Silakan login terlebih dahulu.');
        }
        if (! $this->canAccessMenu($request, 'data-plot')) {
            return redirect()->route('dashboard')
                ->with('status', 'Hak akses ke menu Data Plot tidak tersedia.');
        }

        $blockColumns = ['blockid', 'block_name', 'map_color'];
        if (Schema::hasColumn('blocks', 'max_plots')) {
            $blockColumns[] = 'max_plots';
        }

        $blocks = Block::query()
            ->select($blockColumns)
            ->withCount([
                'gravePlots as total_plots',
                'gravePlots as occupied_plots' => fn ($query) => $query->where('status', 'occupied'),
            ])
            ->with([
                'gravePlots' => fn ($query) => $query
                    ->select([
                        'plotid',
                        'block_id',
                        'plot_number',
                        'row_number',
                        'position_x',
                        'position_y',
                        'width',
                        'height',
                        'status',
                    ])
                    ->orderBy('row_number')
                    ->orderBy('plot_number'),
            ])
            ->orderBy('blockid')
            ->get();

        $plotCards = [];

        foreach ($blocks as $block) {
            $plots = $block->gravePlots;
            $minX = 0.0;
            $minY = 0.0;
            $maxX = 220.0;
            $maxY = 130.0;

            if ($plots->isNotEmpty()) {
                $minX = (float) $plots->min('position_x');
                $minY = (float) $plots->min('position_y');
                $maxX = 0.0;
                $maxY = 0.0;

                foreach ($plots as $plot) {
                    $w = (float) ($plot->width ?? 60);
                    $h = (float) ($plot->height ?? 40);
                    $endX = (float) $plot->position_x + $w;
                    $endY = (float) $plot->position_y + $h;
                    if ($endX > $maxX) {
                        $maxX = $endX;
                    }
                    if ($endY > $maxY) {
                        $maxY = $endY;
                    }
                }
            }

            $maxPlotsPerBlock = max(1, (int) ($block->max_plots ?? 15));
            $targetColumns = max(5, (int) ceil(sqrt($maxPlotsPerBlock)));
            $targetRows = max(3, (int) ceil($maxPlotsPerBlock / $targetColumns));
            $minimumCanvasWidth = max(480, ($targetColumns * 84) + 60);
            $minimumCanvasHeight = max(300, ($targetRows * 62) + 60);

            $canvasWidth = max($minimumCanvasWidth, (int) ceil(($maxX - $minX) + 52));
            $canvasHeight = max($minimumCanvasHeight, (int) ceil(($maxY - $minY) + 52));

            $cardPlots = [];
            foreach ($plots as $plot) {
                $normalizedStatus = ($plot->status ?? 'empty') === 'occupied' ? 'occupied' : 'empty';

                $cardPlots[] = [
                    'plotid' => (int) $plot->plotid,
                    'number' => $plot->plot_number,
                    'row_number' => $plot->row_number,
                    'status' => $normalizedStatus,
                    'x' => ((float) $plot->position_x - $minX) + 24,
                    'y' => ((float) $plot->position_y - $minY) + 24,
                    'width' => (float) ($plot->width ?? 60),
                    'height' => (float) ($plot->height ?? 40),
                ];
            }

            $plotCards[] = [
                'blockid' => (int) $block->blockid,
                'block_name' => $block->block_name,
                'map_color' => $block->map_color ?: '#D8E4DF',
                'total_plots' => (int) $block->total_plots,
                'occupied_plots' => (int) $block->occupied_plots,
                'max_plots' => max(1, (int) ($block->max_plots ?? 15)),
                'canvas_width' => $canvasWidth,
                'canvas_height' => $canvasHeight,
                'plots' => $cardPlots,
            ];
        }

        return view('data-plot', [
            'authUser' => $request->session()->get('auth_user'),
            'plotCards' => $plotCards,
        ]);
    }

    public function dataAlmarhum(Request $request): View|RedirectResponse
    {
        if (! $request->session()->has('auth_user')) {
            return redirect('/login')
                ->with('status', 'Silakan login terlebih dahulu.');
        }
        if (! $this->canAccessMenu($request, 'data-almarhum')) {
            return redirect()->route('dashboard')
                ->with('status', 'Hak akses ke menu Data Almarhum tidak tersedia.');
        }

        $blockColumns = ['blockid', 'block_name', 'map_color'];
        if (Schema::hasColumn('blocks', 'max_plots')) {
            $blockColumns[] = 'max_plots';
        }

        $blocks = Block::query()
            ->select($blockColumns)
            ->withCount([
                'gravePlots as total_plots',
                'gravePlots as occupied_plots' => fn ($query) => $query->where('status', 'occupied'),
            ])
            ->with([
                'gravePlots' => fn ($query) => $query
                    ->select([
                        'plotid',
                        'block_id',
                        'plot_number',
                        'row_number',
                        'position_x',
                        'position_y',
                        'width',
                        'height',
                        'status',
                    ])
                    ->orderBy('row_number')
                    ->orderBy('plot_number'),
            ])
            ->orderBy('blockid')
            ->get();

        $plotIds = $blocks
            ->flatMap(fn ($block) => $block->gravePlots->pluck('plotid'))
            ->values()
            ->all();

        $deceasedByPlot = empty($plotIds)
            ? collect()
            : Deceased::query()
                ->select([
                    'deceasedid',
                    'plotid',
                    'full_name',
                    'gender',
                    'birth_date',
                    'death_date',
                    'burial_date',
                    'religion',
                    'identity_number',
                    'address',
                    'description',
                    'photo_url',
                ])
                ->whereIn('plotid', $plotIds)
                ->orderByDesc('deceasedid')
                ->get()
                ->unique('plotid')
                ->keyBy('plotid');

        $plotCards = [];

        foreach ($blocks as $block) {
            $plots = $block->gravePlots;
            $minX = 0.0;
            $minY = 0.0;
            $maxX = 220.0;
            $maxY = 130.0;

            if ($plots->isNotEmpty()) {
                $minX = (float) $plots->min('position_x');
                $minY = (float) $plots->min('position_y');
                $maxX = 0.0;
                $maxY = 0.0;

                foreach ($plots as $plot) {
                    $w = (float) ($plot->width ?? 60);
                    $h = (float) ($plot->height ?? 40);
                    $endX = (float) $plot->position_x + $w;
                    $endY = (float) $plot->position_y + $h;
                    if ($endX > $maxX) {
                        $maxX = $endX;
                    }
                    if ($endY > $maxY) {
                        $maxY = $endY;
                    }
                }
            }

            $maxPlotsPerBlock = max(1, (int) ($block->max_plots ?? 15));
            $targetColumns = max(5, (int) ceil(sqrt($maxPlotsPerBlock)));
            $targetRows = max(3, (int) ceil($maxPlotsPerBlock / $targetColumns));
            $minimumCanvasWidth = max(480, ($targetColumns * 84) + 60);
            $minimumCanvasHeight = max(300, ($targetRows * 62) + 60);

            $canvasWidth = max($minimumCanvasWidth, (int) ceil(($maxX - $minX) + 52));
            $canvasHeight = max($minimumCanvasHeight, (int) ceil(($maxY - $minY) + 52));

            $cardPlots = [];
            foreach ($plots as $plot) {
                $normalizedStatus = ($plot->status ?? 'empty') === 'occupied' ? 'occupied' : 'empty';
                $deceased = $deceasedByPlot->get($plot->plotid);

                $cardPlots[] = [
                    'plotid' => (int) $plot->plotid,
                    'number' => $plot->plot_number,
                    'status' => $normalizedStatus,
                    'x' => ((float) $plot->position_x - $minX) + 24,
                    'y' => ((float) $plot->position_y - $minY) + 24,
                    'width' => (float) ($plot->width ?? 60),
                    'height' => (float) ($plot->height ?? 40),
                    'deceased_id' => $deceased?->deceasedid,
                    'deceased_name' => $deceased?->full_name,
                    'deceased_gender' => $deceased?->gender,
                    'deceased_birth_date' => $deceased?->birth_date,
                    'deceased_death_date' => $deceased?->death_date,
                    'deceased_burial_date' => $deceased?->burial_date,
                    'deceased_religion' => $deceased?->religion,
                    'deceased_identity_number' => $deceased?->identity_number,
                    'deceased_address' => $deceased?->address,
                    'deceased_description' => $deceased?->description,
                    'deceased_photo_url' => $this->resolvePhotoUrl($deceased?->photo_url),
                ];
            }

            $plotCards[] = [
                'blockid' => (int) $block->blockid,
                'block_name' => $block->block_name,
                'map_color' => $block->map_color ?: '#D8E4DF',
                'total_plots' => (int) $block->total_plots,
                'occupied_plots' => (int) $block->occupied_plots,
                'max_plots' => max(1, (int) ($block->max_plots ?? 15)),
                'canvas_width' => $canvasWidth,
                'canvas_height' => $canvasHeight,
                'plots' => $cardPlots,
            ];
        }

        return view('data-almarhum', [
            'authUser' => $request->session()->get('auth_user'),
            'plotCards' => $plotCards,
        ]);
    }

    public function dataKontakKeluarga(Request $request): View|RedirectResponse
    {
        if (! $request->session()->has('auth_user')) {
            return redirect('/login')
                ->with('status', 'Silakan login terlebih dahulu.');
        }
        if (! $this->canAccessMenu($request, 'data-kontak-keluarga')) {
            return redirect()->route('dashboard')
                ->with('status', 'Hak akses ke menu Data Kontak Keluarga tidak tersedia.');
        }

        $blockColumns = ['blockid', 'block_name', 'map_color'];
        if (Schema::hasColumn('blocks', 'max_plots')) {
            $blockColumns[] = 'max_plots';
        }

        $blocks = Block::query()
            ->select($blockColumns)
            ->withCount([
                'gravePlots as total_plots',
                'gravePlots as occupied_plots' => fn ($query) => $query->where('status', 'occupied'),
            ])
            ->with([
                'gravePlots' => fn ($query) => $query
                    ->select([
                        'plotid',
                        'block_id',
                        'plot_number',
                        'row_number',
                        'position_x',
                        'position_y',
                        'width',
                        'height',
                        'status',
                    ])
                    ->orderBy('row_number')
                    ->orderBy('plot_number'),
            ])
            ->orderBy('blockid')
            ->get();

        $plotIds = $blocks
            ->flatMap(fn ($block) => $block->gravePlots->pluck('plotid'))
            ->values()
            ->all();

        $deceasedByPlot = empty($plotIds)
            ? collect()
            : Deceased::query()
                ->select([
                    'deceasedid',
                    'plotid',
                    'full_name',
                ])
                ->whereIn('plotid', $plotIds)
                ->orderByDesc('deceasedid')
                ->get()
                ->unique('plotid')
                ->keyBy('plotid');

        $deceasedIds = $deceasedByPlot
            ->pluck('deceasedid')
            ->filter()
            ->values()
            ->all();

        $familyByDeceased = empty($deceasedIds)
            ? collect()
            : DB::table('families')
                ->select([
                    'familyid',
                    'deceased_id',
                    'family_name',
                    'relationship_status',
                    'phone_number',
                    'email',
                    'address',
                    'notes',
                ])
                ->whereIn('deceased_id', $deceasedIds)
                ->orderBy('familyid')
                ->get()
                ->groupBy('deceased_id');

        $plotCards = [];

        foreach ($blocks as $block) {
            $plots = $block->gravePlots;
            $minX = 0.0;
            $minY = 0.0;
            $maxX = 220.0;
            $maxY = 130.0;

            if ($plots->isNotEmpty()) {
                $minX = (float) $plots->min('position_x');
                $minY = (float) $plots->min('position_y');
                $maxX = 0.0;
                $maxY = 0.0;

                foreach ($plots as $plot) {
                    $w = (float) ($plot->width ?? 60);
                    $h = (float) ($plot->height ?? 40);
                    $endX = (float) $plot->position_x + $w;
                    $endY = (float) $plot->position_y + $h;
                    if ($endX > $maxX) {
                        $maxX = $endX;
                    }
                    if ($endY > $maxY) {
                        $maxY = $endY;
                    }
                }
            }

            $maxPlotsPerBlock = max(1, (int) ($block->max_plots ?? 15));
            $targetColumns = max(5, (int) ceil(sqrt($maxPlotsPerBlock)));
            $targetRows = max(3, (int) ceil($maxPlotsPerBlock / $targetColumns));
            $minimumCanvasWidth = max(480, ($targetColumns * 84) + 60);
            $minimumCanvasHeight = max(300, ($targetRows * 62) + 60);

            $canvasWidth = max($minimumCanvasWidth, (int) ceil(($maxX - $minX) + 52));
            $canvasHeight = max($minimumCanvasHeight, (int) ceil(($maxY - $minY) + 52));

            $cardPlots = [];
            foreach ($plots as $plot) {
                $normalizedStatus = ($plot->status ?? 'empty') === 'occupied' ? 'occupied' : 'empty';
                $deceased = $deceasedByPlot->get($plot->plotid);
                $familyContacts = collect($familyByDeceased->get((int) ($deceased?->deceasedid ?? 0), collect()))
                    ->map(fn ($family) => [
                        'familyid' => (int) $family->familyid,
                        'family_name' => $family->family_name,
                        'relationship_status' => $family->relationship_status,
                        'phone_number' => $family->phone_number,
                        'email' => $family->email,
                        'address' => $family->address,
                        'notes' => $family->notes,
                    ])
                    ->values()
                    ->all();

                $cardPlots[] = [
                    'plotid' => (int) $plot->plotid,
                    'number' => $plot->plot_number,
                    'status' => $normalizedStatus,
                    'x' => ((float) $plot->position_x - $minX) + 24,
                    'y' => ((float) $plot->position_y - $minY) + 24,
                    'width' => (float) ($plot->width ?? 60),
                    'height' => (float) ($plot->height ?? 40),
                    'deceased_id' => $deceased?->deceasedid,
                    'deceased_name' => $deceased?->full_name,
                    'family_contacts' => $familyContacts,
                ];
            }

            $plotCards[] = [
                'blockid' => (int) $block->blockid,
                'block_name' => $block->block_name,
                'map_color' => $block->map_color ?: '#D8E4DF',
                'total_plots' => (int) $block->total_plots,
                'occupied_plots' => (int) $block->occupied_plots,
                'max_plots' => max(1, (int) ($block->max_plots ?? 15)),
                'canvas_width' => $canvasWidth,
                'canvas_height' => $canvasHeight,
                'plots' => $cardPlots,
            ];
        }

        return view('data-kontak-keluarga', [
            'authUser' => $request->session()->get('auth_user'),
            'plotCards' => $plotCards,
        ]);
    }

    public function storeDataKontakKeluarga(Request $request): JsonResponse|RedirectResponse
    {
        if (! $request->session()->has('auth_user')) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'message' => 'Silakan login terlebih dahulu.',
                ], 401);
            }

            return redirect('/login')
                ->with('status', 'Silakan login terlebih dahulu.');
        }
        if (! $this->canAccessMenu($request, 'data-kontak-keluarga')) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'message' => 'Hak akses ke menu Data Kontak Keluarga tidak tersedia.',
                ], 403);
            }

            return redirect()->route('dashboard')
                ->with('status', 'Hak akses ke menu Data Kontak Keluarga tidak tersedia.');
        }

        $validated = $request->validateWithBag('familyContactForm', [
            'deceased_id' => ['required', 'integer', Rule::exists('deceased', 'deceasedid')],
            'family_name' => ['required', 'string', 'max:150'],
            'relationship_status' => ['nullable', 'string', 'max:100'],
            'phone_number' => ['nullable', 'string', 'max:30'],
            'email' => ['nullable', 'email', 'max:255'],
            'address' => ['nullable', 'string', 'max:500'],
            'notes' => ['nullable', 'string', 'max:2000'],
        ]);

        $deceased = Deceased::query()
            ->select(['deceasedid', 'plotid', 'full_name'])
            ->find((int) $validated['deceased_id']);

        if (! $deceased) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'message' => 'Data almarhum tidak ditemukan.',
                ], 404);
            }

            return redirect()
                ->route('dashboard.data-kontak-keluarga')
                ->withErrors(['deceased_id' => 'Data almarhum tidak ditemukan.'], 'familyContactForm');
        }

        $payload = [
            'deceased_id' => (int) $validated['deceased_id'],
            'family_name' => $validated['family_name'],
            'relationship_status' => $validated['relationship_status'] ?: null,
            'phone_number' => $validated['phone_number'] ?: null,
            'email' => $validated['email'] ?: null,
            'address' => $validated['address'] ?: null,
            'notes' => $validated['notes'] ?: null,
        ];

        if (Schema::hasColumn('families', 'created_at')) {
            $payload['created_at'] = now();
        }
        if (Schema::hasColumn('families', 'updated_at')) {
            $payload['updated_at'] = now();
        }

        $familyId = (int) DB::table('families')->insertGetId($payload, 'familyid');

        $createdFamily = DB::table('families')
            ->select([
                'familyid',
                'deceased_id',
                'family_name',
                'relationship_status',
                'phone_number',
                'email',
                'address',
                'notes',
            ])
            ->where('familyid', $familyId)
            ->first();

        $this->writeActivityLog(
            $request,
            'Tambah Kontak Keluarga',
            'Menambah kontak keluarga "' . $payload['family_name'] . '" untuk almarhum #' . (int) $deceased->deceasedid . ' "' . $deceased->full_name . '" pada plot #' . (int) $deceased->plotid . '.'
        );

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'message' => 'Kontak keluarga berhasil ditambahkan.',
                'data' => [
                    'familyid' => (int) ($createdFamily->familyid ?? $familyId),
                    'deceased_id' => (int) ($createdFamily->deceased_id ?? (int) $validated['deceased_id']),
                    'family_name' => (string) ($createdFamily->family_name ?? $payload['family_name']),
                    'relationship_status' => $createdFamily->relationship_status ?? $payload['relationship_status'],
                    'phone_number' => $createdFamily->phone_number ?? $payload['phone_number'],
                    'email' => $createdFamily->email ?? $payload['email'],
                    'address' => $createdFamily->address ?? $payload['address'],
                    'notes' => $createdFamily->notes ?? $payload['notes'],
                ],
            ]);
        }

        return redirect()
            ->route('dashboard.data-kontak-keluarga')
            ->with('status', 'Kontak keluarga berhasil ditambahkan.');
    }

    public function destroyDataKontakKeluarga(Request $request, int $familyid): JsonResponse|RedirectResponse
    {
        if (! $request->session()->has('auth_user')) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'message' => 'Silakan login terlebih dahulu.',
                ], 401);
            }

            return redirect('/login')
                ->with('status', 'Silakan login terlebih dahulu.');
        }
        if (! $this->canAccessMenu($request, 'data-kontak-keluarga')) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'message' => 'Hak akses ke menu Data Kontak Keluarga tidak tersedia.',
                ], 403);
            }

            return redirect()->route('dashboard')
                ->with('status', 'Hak akses ke menu Data Kontak Keluarga tidak tersedia.');
        }

        $family = DB::table('families')
            ->where('familyid', $familyid)
            ->first();

        if (! $family) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'message' => 'Data kontak keluarga tidak ditemukan.',
                ], 404);
            }

            return redirect()
                ->route('dashboard.data-kontak-keluarga')
                ->with('status', 'Data kontak keluarga tidak ditemukan.');
        }

        $deceased = Deceased::query()
            ->select(['deceasedid', 'plotid', 'full_name'])
            ->find((int) ($family->deceased_id ?? 0));

        DB::transaction(function () use ($request, $family, $familyid): void {
            $familyPayload = (array) $family;
            $this->archiveDeletedData(
                $request,
                'family',
                (int) $familyid,
                'Kontak keluarga "' . ((string) ($family->family_name ?? '-')) . '"',
                $familyPayload
            );

            DB::table('families')
                ->where('familyid', $familyid)
                ->delete();
        });

        $detail = 'Menghapus kontak keluarga #' . (int) $familyid . ' "' . ((string) ($family->family_name ?? '-')) . '"';
        if ($deceased) {
            $detail .= ' untuk almarhum #' . (int) $deceased->deceasedid . ' "' . $deceased->full_name . '" pada plot #' . (int) $deceased->plotid;
        }
        $detail .= '.';

        $this->writeActivityLog(
            $request,
            'Hapus Kontak Keluarga',
            $detail
        );

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'message' => 'Kontak keluarga berhasil dihapus.',
                'data' => [
                    'familyid' => (int) $familyid,
                ],
            ]);
        }

        return redirect()
            ->route('dashboard.data-kontak-keluarga')
            ->with('status', 'Kontak keluarga berhasil dihapus.');
    }

    public function storeDataAlmarhum(Request $request): JsonResponse|RedirectResponse
    {
        if (! $request->session()->has('auth_user')) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'message' => 'Silakan login terlebih dahulu.',
                ], 401);
            }

            return redirect('/login')
                ->with('status', 'Silakan login terlebih dahulu.');
        }
        if (! $this->canAccessMenu($request, 'data-almarhum')) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'message' => 'Hak akses ke menu Data Almarhum tidak tersedia.',
                ], 403);
            }

            return redirect()->route('dashboard')
                ->with('status', 'Hak akses ke menu Data Almarhum tidak tersedia.');
        }

        $validated = $this->validateDeceasedPayload($request);
        $plotId = (int) $validated['plotid'];

        $existing = Deceased::query()
            ->where('plotid', $plotId)
            ->exists();

        if ($existing) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'message' => 'Plot ini sudah terisi data almarhum.',
                    'errors' => [
                        'plotid' => ['Plot ini sudah terisi data almarhum.'],
                    ],
                ], 422);
            }

            return back()
                ->withInput()
                ->withErrors([
                    'plotid' => 'Plot ini sudah terisi data almarhum.',
                ], 'almarhumForm');
        }

        $createdDeceased = Deceased::query()->create([
            'plotid' => $plotId,
            'full_name' => $validated['full_name'],
            'gender' => $validated['gender'] ?: null,
            'birth_date' => $validated['birth_date'] ?: null,
            'death_date' => $validated['death_date'] ?: null,
            'burial_date' => $validated['burial_date'] ?: null,
            'religion' => $validated['religion'] ?: null,
            'identity_number' => $validated['identity_number'] ?: null,
            'address' => $validated['address'] ?: null,
            'description' => $validated['description'] ?: null,
            'photo_url' => $this->storeDeceasedPhoto($request),
        ]);

        GravePlot::query()
            ->where('plotid', $plotId)
            ->update(['status' => 'occupied']);

        $this->writeActivityLog(
            $request,
            'Tambah Almarhum',
            'Menambah data almarhum #' . (int) $createdDeceased->deceasedid . ' "' . $createdDeceased->full_name . '" pada plot #' . $plotId . '.'
        );

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'message' => 'Data almarhum berhasil ditambahkan.',
                'data' => [
                    'deceasedid' => (int) $createdDeceased->deceasedid,
                    'plotid' => (int) $createdDeceased->plotid,
                    'full_name' => $createdDeceased->full_name,
                    'gender' => $createdDeceased->gender,
                    'birth_date' => $createdDeceased->birth_date,
                    'death_date' => $createdDeceased->death_date,
                    'burial_date' => $createdDeceased->burial_date,
                    'religion' => $createdDeceased->religion,
                    'identity_number' => $createdDeceased->identity_number,
                    'address' => $createdDeceased->address,
                    'description' => $createdDeceased->description,
                    'photo_url' => $this->resolvePhotoUrl($createdDeceased->photo_url),
                ],
            ]);
        }

        return redirect()
            ->route('dashboard.data-almarhum')
            ->with('status', 'Data almarhum berhasil ditambahkan.');
    }

    public function updateDataAlmarhum(Request $request, Deceased $deceased): JsonResponse|RedirectResponse
    {
        if (! $request->session()->has('auth_user')) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'message' => 'Silakan login terlebih dahulu.',
                ], 401);
            }

            return redirect('/login')
                ->with('status', 'Silakan login terlebih dahulu.');
        }
        if (! $this->canAccessMenu($request, 'data-almarhum')) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'message' => 'Hak akses ke menu Data Almarhum tidak tersedia.',
                ], 403);
            }

            return redirect()->route('dashboard')
                ->with('status', 'Hak akses ke menu Data Almarhum tidak tersedia.');
        }

        $validated = $this->validateDeceasedPayload($request);
        $plotId = (int) $validated['plotid'];

        $existing = Deceased::query()
            ->where('plotid', $plotId)
            ->where('deceasedid', '!=', (int) $deceased->deceasedid)
            ->exists();

        if ($existing) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'message' => 'Plot ini sudah terisi data almarhum lain.',
                    'errors' => [
                        'plotid' => ['Plot ini sudah terisi data almarhum lain.'],
                    ],
                ], 422);
            }

            return back()
                ->withInput()
                ->withErrors([
                    'plotid' => 'Plot ini sudah terisi data almarhum lain.',
                ], 'almarhumForm');
        }

        $oldPlotId = (int) $deceased->plotid;
        $photoPath = $deceased->photo_url;
        $newPhotoPath = $this->storeDeceasedPhoto($request);
        if ($newPhotoPath) {
            if ($photoPath && ! Str::startsWith($photoPath, ['http://', 'https://', '/'])) {
                Storage::disk('public')->delete($photoPath);
            }
            $photoPath = $newPhotoPath;
        }

        $oldDeceased = [
            'plotid' => (int) $deceased->plotid,
            'full_name' => (string) $deceased->full_name,
            'gender' => $deceased->gender,
            'birth_date' => $deceased->birth_date,
            'death_date' => $deceased->death_date,
            'burial_date' => $deceased->burial_date,
            'religion' => $deceased->religion,
            'identity_number' => $deceased->identity_number,
            'address' => $deceased->address,
            'description' => $deceased->description,
        ];

        $deceased->fill([
            'plotid' => $plotId,
            'full_name' => $validated['full_name'],
            'gender' => $validated['gender'] ?: null,
            'birth_date' => $validated['birth_date'] ?: null,
            'death_date' => $validated['death_date'] ?: null,
            'burial_date' => $validated['burial_date'] ?: null,
            'religion' => $validated['religion'] ?: null,
            'identity_number' => $validated['identity_number'] ?: null,
            'address' => $validated['address'] ?: null,
            'description' => $validated['description'] ?: null,
            'photo_url' => $photoPath,
        ]);
        $deceased->save();

        GravePlot::query()
            ->where('plotid', $plotId)
            ->update(['status' => 'occupied']);

        if ($oldPlotId !== $plotId) {
            $stillOccupied = Deceased::query()->where('plotid', $oldPlotId)->exists();
            if (! $stillOccupied) {
                GravePlot::query()
                    ->where('plotid', $oldPlotId)
                    ->where('status', 'occupied')
                    ->update(['status' => 'empty']);
            }
        }

        $changes = [];
        $this->appendChangeDetail($changes, 'nama', $oldDeceased['full_name'], $deceased->full_name);
        $this->appendChangeDetail($changes, 'plotid', $oldDeceased['plotid'], $deceased->plotid);
        $this->appendChangeDetail($changes, 'gender', $oldDeceased['gender'], $deceased->gender);
        $this->appendChangeDetail($changes, 'tanggal lahir', $oldDeceased['birth_date'], $deceased->birth_date);
        $this->appendChangeDetail($changes, 'tanggal meninggal', $oldDeceased['death_date'], $deceased->death_date);
        $this->appendChangeDetail($changes, 'tanggal pemakaman', $oldDeceased['burial_date'], $deceased->burial_date);
        $this->appendChangeDetail($changes, 'agama', $oldDeceased['religion'], $deceased->religion);
        $this->appendChangeDetail($changes, 'NIK', $oldDeceased['identity_number'], $deceased->identity_number);
        $this->appendChangeDetail($changes, 'alamat', $oldDeceased['address'], $deceased->address);
        $this->appendChangeDetail($changes, 'deskripsi', $oldDeceased['description'], $deceased->description);

        $detail = 'Mengedit data almarhum #' . (int) $deceased->deceasedid . '. ';
        $detail .= empty($changes) ? 'Tidak ada perubahan nilai utama.' : implode(', ', $changes) . '.';
        $this->writeActivityLog($request, 'Edit Almarhum', $detail);

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'message' => 'Data almarhum berhasil diperbarui.',
                'data' => [
                    'deceasedid' => (int) $deceased->deceasedid,
                    'plotid' => (int) $deceased->plotid,
                    'full_name' => $deceased->full_name,
                    'gender' => $deceased->gender,
                    'birth_date' => $deceased->birth_date,
                    'death_date' => $deceased->death_date,
                    'burial_date' => $deceased->burial_date,
                    'religion' => $deceased->religion,
                    'identity_number' => $deceased->identity_number,
                    'address' => $deceased->address,
                    'description' => $deceased->description,
                    'photo_url' => $this->resolvePhotoUrl($deceased->photo_url),
                ],
            ]);
        }

        return redirect()
            ->route('dashboard.data-almarhum')
            ->with('status', 'Data almarhum berhasil diperbarui.');
    }

    public function destroyDataAlmarhum(Request $request, Deceased $deceased): JsonResponse|RedirectResponse
    {
        if (! $request->session()->has('auth_user')) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'message' => 'Silakan login terlebih dahulu.',
                ], 401);
            }

            return redirect('/login')
                ->with('status', 'Silakan login terlebih dahulu.');
        }
        if (! $this->canAccessMenu($request, 'data-almarhum')) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'message' => 'Hak akses ke menu Data Almarhum tidak tersedia.',
                ], 403);
            }

            return redirect()->route('dashboard')
                ->with('status', 'Hak akses ke menu Data Almarhum tidak tersedia.');
        }

        $plotId = (int) $deceased->plotid;
        $deceasedId = (int) $deceased->deceasedid;
        $deceasedName = (string) $deceased->full_name;
        $photoPath = $deceased->photo_url;
        DB::transaction(function () use ($request, $deceased, $deceasedId, $deceasedName, $plotId): void {
            $this->archiveDeletedData(
                $request,
                'deceased',
                $deceasedId,
                'Almarhum "' . $deceasedName . '" (plot #' . $plotId . ')',
                $deceased->getAttributes()
            );

            $deceased->delete();
        });

        $stillOccupied = Deceased::query()->where('plotid', $plotId)->exists();
        if (! $stillOccupied) {
            GravePlot::query()
                ->where('plotid', $plotId)
                ->where('status', 'occupied')
                ->update(['status' => 'empty']);
        }

        $this->writeActivityLog(
            $request,
            'Hapus Almarhum',
            'Menghapus data almarhum #' . $deceasedId . ' "' . $deceasedName . '" dari plot #' . $plotId . '.'
        );

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'message' => 'Data almarhum berhasil dihapus.',
                'data' => [
                    'deceasedid' => $deceasedId,
                    'plotid' => $plotId,
                ],
            ]);
        }

        return redirect()
            ->route('dashboard.data-almarhum')
            ->with('status', 'Data almarhum berhasil dihapus.');
    }

    public function dataUser(Request $request): View|RedirectResponse
    {
        if (! $request->session()->has('auth_user')) {
            return redirect('/login')
                ->with('status', 'Silakan login terlebih dahulu.');
        }
        if (! $this->canAccessMenu($request, 'data-user')) {
            return redirect()->route('dashboard')
                ->with('status', 'Hak akses ke menu Data User tidak tersedia.');
        }

        $userColumns = Schema::hasTable('user') ? Schema::getColumnListing('user') : [];
        $resolveColumn = static function (array $columns, array $candidates): ?string {
            foreach ($candidates as $candidate) {
                if (in_array($candidate, $columns, true)) {
                    return $candidate;
                }
            }

            return null;
        };

        $userFullNameColumn = $resolveColumn($userColumns, ['full_name', 'name', 'fullname', 'employee_name']);
        $userEmailColumn = $resolveColumn($userColumns, ['email', 'email_address', 'mail']);
        $userPhoneColumn = $resolveColumn($userColumns, ['phone_number', 'phonenumber', 'phone', 'phone_no', 'no_hp']);

        $fullNameExpr = $userFullNameColumn ? ('user.' . $userFullNameColumn) : 'NULL';
        $emailExpr = $userEmailColumn ? ('user.' . $userEmailColumn) : 'NULL';
        $phoneExpr = $userPhoneColumn ? ('user.' . $userPhoneColumn) : 'NULL';

        $usersQuery = LegacyUser::query()
            ->select([
                'user.userid',
                'user.username',
                'user.levelid',
            ]);

        if (Schema::hasTable('employer') && Schema::hasColumn('employer', 'userid')) {
            $employerColumns = Schema::getColumnListing('employer');
            $usersQuery->leftJoin('employer', 'employer.userid', '=', 'user.userid');

            $employerFullNameColumn = $resolveColumn($employerColumns, ['full_name', 'name', 'fullname', 'employee_name']);
            $employerEmailColumn = $resolveColumn($employerColumns, ['email', 'email_address', 'mail']);
            $employerPhoneColumn = $resolveColumn($employerColumns, ['phone_number', 'phonenumber', 'phone', 'phone_no', 'no_hp']);

            if ($employerFullNameColumn) {
                $fullNameExpr = 'COALESCE(employer.' . $employerFullNameColumn . ', ' . $fullNameExpr . ')';
            }
            if ($employerEmailColumn) {
                $emailExpr = 'COALESCE(employer.' . $employerEmailColumn . ', ' . $emailExpr . ')';
            }
            if ($employerPhoneColumn) {
                $phoneExpr = 'COALESCE(employer.' . $employerPhoneColumn . ', ' . $phoneExpr . ')';
            }
        }

        $usersQuery->addSelect([
            DB::raw($fullNameExpr . ' as full_name'),
            DB::raw($emailExpr . ' as email'),
            DB::raw($phoneExpr . ' as phone_number'),
        ]);

        if (Schema::hasTable('level') && Schema::hasColumn('level', 'levelname')) {
            $usersQuery->leftJoin('level', 'level.levelid', '=', 'user.levelid')
                ->addSelect('level.levelname');
        }

        $users = $usersQuery
            ->orderBy('userid')
            ->get();

        $levels = collect();
        if (Schema::hasTable('level') && Schema::hasColumn('level', 'levelid')) {
            $levelQuery = DB::table('level')
                ->select('levelid')
                ->orderBy('levelid');

            if (Schema::hasColumn('level', 'levelname')) {
                $levelQuery->addSelect('levelname');
            }

            $levels = $levelQuery->get();
        }

        return view('data-user', [
            'authUser' => $request->session()->get('auth_user'),
            'users' => $users,
            'levels' => $levels,
        ]);
    }

    public function activityLog(Request $request): View|RedirectResponse|JsonResponse
    {
        if (! $request->session()->has('auth_user')) {
            return redirect('/login')
                ->with('status', 'Silakan login terlebih dahulu.');
        }
        if (! $this->canAccessMenu($request, 'activity-log')) {
            return redirect()->route('dashboard')
                ->with('status', 'Hak akses ke menu Activity Log tidak tersedia.');
        }

        $logs = collect();

        if (Schema::hasTable('activity_logs')) {
            $timezone = config('app.timezone', 'Asia/Jakarta');
            $logsQuery = DB::table('activity_logs')
                ->select([
                    'activity_logs.name',
                    'activity_logs.username',
                    'activity_logs.ip_address',
                    'activity_logs.longitude',
                    'activity_logs.latitude',
                    'activity_logs.action',
                    'activity_logs.detail',
                    'activity_logs.created_at',
                ]);

            $this->applyActivityLogVisibilityFilter($request, $logsQuery);

            $logs = $logsQuery
                ->orderByDesc('activity_logs.created_at')
                ->paginate(20)
                ->withQueryString();

            $logs->getCollection()->transform(function ($row) use ($timezone) {
                    $timestamp = $row->created_at
                        ? Carbon::parse((string) $row->created_at)->setTimezone($timezone)
                        : null;

                    return [
                        'tanggal' => $timestamp?->format('d-m-Y') ?? '-',
                        'jam' => $timestamp?->format('H:i:s') ?? '-',
                        'nama' => $row->name ?: '-',
                        'username' => $row->username ?: '-',
                        'ip_address' => $row->ip_address ?: '-',
                        'longitude' => $row->longitude ?: '-',
                        'latitude' => $row->latitude ?: '-',
                        'aksi' => $row->action ?: '-',
                        'detail' => $row->detail ?: '-',
                    ];
                });
        }

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'tbody' => view('partials.activity-log-rows', ['logs' => $logs])->render(),
                'pagination' => view('partials.ajax-pagination', ['paginator' => $logs])->render(),
            ]);
        }

        return view('activity-log', [
            'authUser' => $request->session()->get('auth_user'),
            'logs' => $logs,
        ]);
    }

    public function restoreData(Request $request): View|RedirectResponse|JsonResponse
    {
        if (! $request->session()->has('auth_user')) {
            return redirect('/login')
                ->with('status', 'Silakan login terlebih dahulu.');
        }
        if (! $this->canAccessMenu($request, 'restore-data')) {
            return redirect()->route('dashboard')
                ->with('status', 'Hak akses ke menu Restore Data tidak tersedia.');
        }

        $rows = collect();
        $entityTypeFilter = trim((string) $request->query('entity_type', ''));
        $allowedEntityTypes = ['block', 'plot', 'deceased', 'family'];
        if (! in_array($entityTypeFilter, $allowedEntityTypes, true)) {
            $entityTypeFilter = '';
        }

        if ($this->hasUsableTable('restore_data')) {
            try {
                $timezone = config('app.timezone', 'Asia/Jakarta');
                $query = DB::table('restore_data')
                    ->select([
                        'restoreid',
                        'entity_type',
                        'entity_id',
                        'entity_label',
                        'deleted_by_username',
                        'ip_address',
                        'longitude',
                        'latitude',
                        'deleted_at',
                    ]);

                if ($entityTypeFilter !== '') {
                    $query->where('entity_type', $entityTypeFilter);
                }

                $rows = $query
                    ->orderByDesc('deleted_at')
                    ->orderByDesc('restoreid')
                    ->paginate(20)
                    ->withQueryString();

                $rows->getCollection()->transform(function ($row) use ($timezone) {
                        $timestamp = $row->deleted_at
                            ? Carbon::parse((string) $row->deleted_at)->setTimezone($timezone)
                            : null;

                        return [
                            'id' => (int) ($row->restoreid ?? 0),
                            'tanggal' => $timestamp?->format('d-m-Y') ?? '-',
                            'jam' => $timestamp?->format('H:i:s') ?? '-',
                            'username' => $row->deleted_by_username ?: '-',
                            'ip_address' => $row->ip_address ?: '-',
                            'longitude' => $row->longitude ?: '-',
                            'latitude' => $row->latitude ?: '-',
                            'jenis' => $this->restoreEntityTypeLabel((string) ($row->entity_type ?? '')),
                            'data' => $row->entity_label ?: ('ID ' . ((string) ($row->entity_id ?? '-'))),
                        ];
                    });
            } catch (\Throwable $e) {
                if (! $this->isUnavailableTableException($e, 'restore_data')) {
                    throw $e;
                }

                report($e);
            }
        }

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'tbody' => view('partials.restore-data-rows', ['items' => $rows])->render(),
                'pagination' => view('partials.ajax-pagination', ['paginator' => $rows])->render(),
            ]);
        }

        return view('restore-data', [
            'authUser' => $request->session()->get('auth_user'),
            'items' => $rows,
            'entityTypeFilter' => $entityTypeFilter,
        ]);
    }

    public function restoreDataItem(Request $request, int $restoreid): JsonResponse|RedirectResponse
    {
        if (! $request->session()->has('auth_user')) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json(['message' => 'Silakan login terlebih dahulu.'], 401);
            }

            return redirect('/login')
                ->with('status', 'Silakan login terlebih dahulu.');
        }
        if (! $this->canAccessMenu($request, 'restore-data')) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json(['message' => 'Hak akses ke menu Restore Data tidak tersedia.'], 403);
            }

            return redirect()->route('dashboard')
                ->with('status', 'Hak akses ke menu Restore Data tidak tersedia.');
        }
        if (! $this->hasUsableTable('restore_data')) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json(['message' => 'Tabel restore data belum tersedia.'], 422);
            }

            return redirect()->route('dashboard.restore-data')
                ->with('status', 'Tabel restore data belum tersedia.');
        }

        try {
            $item = DB::table('restore_data')
                ->where('restoreid', $restoreid)
                ->first();
        } catch (\Throwable $e) {
            if (! $this->isUnavailableTableException($e, 'restore_data')) {
                throw $e;
            }

            report($e);

            if ($request->expectsJson() || $request->ajax()) {
                return response()->json(['message' => 'Tabel restore data belum tersedia.'], 422);
            }

            return redirect()->route('dashboard.restore-data')
                ->with('status', 'Tabel restore data belum tersedia.');
        }

        if (! $item) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json(['message' => 'Data restore tidak ditemukan.'], 404);
            }

            return redirect()->route('dashboard.restore-data')
                ->with('status', 'Data restore tidak ditemukan.');
        }

        $payload = json_decode((string) ($item->payload ?? '{}'), true);
        if (! is_array($payload)) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json(['message' => 'Payload restore tidak valid.'], 422);
            }

            return redirect()->route('dashboard.restore-data')
                ->with('status', 'Payload restore tidak valid.');
        }

        [$ok, $message] = $this->performRestoreEntity((string) ($item->entity_type ?? ''), $payload);
        if (! $ok) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json(['message' => $message], 422);
            }

            return redirect()->route('dashboard.restore-data')
                ->with('status', $message);
        }

        try {
            DB::table('restore_data')
                ->where('restoreid', (int) $item->restoreid)
                ->delete();
        } catch (\Throwable $e) {
            if (! $this->isUnavailableTableException($e, 'restore_data')) {
                throw $e;
            }

            report($e);

            if ($request->expectsJson() || $request->ajax()) {
                return response()->json(['message' => 'Tabel restore data belum tersedia.'], 422);
            }

            return redirect()->route('dashboard.restore-data')
                ->with('status', 'Tabel restore data belum tersedia.');
        }

        $this->writeActivityLog(
            $request,
            'Restore Data',
            'Mengembalikan data ' . $this->restoreEntityTypeLabel((string) ($item->entity_type ?? '')) . ' (' . ($item->entity_label ?: ('ID ' . ($item->entity_id ?? '-'))) . ').'
        );

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json(['message' => 'Data berhasil dikembalikan.']);
        }

        return redirect()->route('dashboard.restore-data')
            ->with('status', 'Data berhasil dikembalikan.');
    }

    public function forceDeleteRestoreDataItem(Request $request, int $restoreid): JsonResponse|RedirectResponse
    {
        if (! $request->session()->has('auth_user')) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json(['message' => 'Silakan login terlebih dahulu.'], 401);
            }

            return redirect('/login')
                ->with('status', 'Silakan login terlebih dahulu.');
        }
        if (! $this->canAccessMenu($request, 'restore-data')) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json(['message' => 'Hak akses ke menu Restore Data tidak tersedia.'], 403);
            }

            return redirect()->route('dashboard')
                ->with('status', 'Hak akses ke menu Restore Data tidak tersedia.');
        }
        if (! $this->hasUsableTable('restore_data')) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json(['message' => 'Tabel restore data belum tersedia.'], 422);
            }

            return redirect()->route('dashboard.restore-data')
                ->with('status', 'Tabel restore data belum tersedia.');
        }

        try {
            $item = DB::table('restore_data')
                ->where('restoreid', $restoreid)
                ->first();
        } catch (\Throwable $e) {
            if (! $this->isUnavailableTableException($e, 'restore_data')) {
                throw $e;
            }

            report($e);

            if ($request->expectsJson() || $request->ajax()) {
                return response()->json(['message' => 'Tabel restore data belum tersedia.'], 422);
            }

            return redirect()->route('dashboard.restore-data')
                ->with('status', 'Tabel restore data belum tersedia.');
        }

        if (! $item) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json(['message' => 'Data restore tidak ditemukan.'], 404);
            }

            return redirect()->route('dashboard.restore-data')
                ->with('status', 'Data restore tidak ditemukan.');
        }

        $payload = json_decode((string) ($item->payload ?? '{}'), true);
        if (
            is_array($payload)
            && (string) ($item->entity_type ?? '') === 'deceased'
            && isset($payload['photo_url'])
            && is_string($payload['photo_url'])
            && $payload['photo_url'] !== ''
            && ! Str::startsWith($payload['photo_url'], ['http://', 'https://', '/'])
        ) {
            Storage::disk('public')->delete($payload['photo_url']);
        }

        try {
            DB::table('restore_data')
                ->where('restoreid', (int) $item->restoreid)
                ->delete();
        } catch (\Throwable $e) {
            if (! $this->isUnavailableTableException($e, 'restore_data')) {
                throw $e;
            }

            report($e);

            if ($request->expectsJson() || $request->ajax()) {
                return response()->json(['message' => 'Tabel restore data belum tersedia.'], 422);
            }

            return redirect()->route('dashboard.restore-data')
                ->with('status', 'Tabel restore data belum tersedia.');
        }

        $this->writeActivityLog(
            $request,
            'Hapus Permanen Restore Data',
            'Menghapus permanen arsip ' . $this->restoreEntityTypeLabel((string) ($item->entity_type ?? '')) . ' (' . ($item->entity_label ?: ('ID ' . ($item->entity_id ?? '-'))) . ').'
        );

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json(['message' => 'Data arsip berhasil dihapus permanen.']);
        }

        return redirect()->route('dashboard.restore-data')
            ->with('status', 'Data arsip berhasil dihapus permanen.');
    }

    public function hakAkses(Request $request): View|RedirectResponse
    {
        if (! $request->session()->has('auth_user')) {
            return redirect('/login')
                ->with('status', 'Silakan login terlebih dahulu.');
        }
        if (! $this->canAccessMenu($request, 'hak-akses')) {
            return redirect()->route('dashboard')
                ->with('status', 'Hak akses ke menu Hak Akses tidak tersedia.');
        }

        $levels = collect();
        if (Schema::hasTable('level') && Schema::hasColumn('level', 'levelid')) {
            $levelQuery = DB::table('level')
                ->select(['levelid'])
                ->orderBy('levelid');

            if (Schema::hasColumn('level', 'levelname')) {
                $levelQuery->addSelect('levelname');
            }

            $levels = $levelQuery->get();
        }

        $menuOptions = $this->permissionMenuOptions();
        $matrix = [];
        if (Schema::hasTable('level_sidebar_access')) {
            $rows = DB::table('level_sidebar_access')
                ->select(['levelid', 'menu_key'])
                ->get();

            foreach ($rows as $row) {
                $matrix[(int) $row->levelid][$row->menu_key] = true;
            }
        }

        return view('hak-akses', [
            'authUser' => $request->session()->get('auth_user'),
            'levels' => $levels,
            'menuOptions' => $menuOptions,
            'matrix' => $matrix,
        ]);
    }

    public function updateHakAkses(Request $request): RedirectResponse
    {
        if (! $request->session()->has('auth_user')) {
            return redirect('/login')
                ->with('status', 'Silakan login terlebih dahulu.');
        }
        if (! $this->canAccessMenu($request, 'hak-akses')) {
            return redirect()->route('dashboard')
                ->with('status', 'Hak akses ke menu Hak Akses tidak tersedia.');
        }
        if (! Schema::hasTable('level_sidebar_access') || ! Schema::hasTable('level')) {
            return redirect()
                ->route('dashboard.hak-akses')
                ->with('status', 'Tabel hak akses belum tersedia. Jalankan migrasi terlebih dahulu.');
        }

        $menuKeys = array_keys($this->permissionMenuOptions());
        $levels = DB::table('level')->select('levelid')->get()->pluck('levelid')->map(fn ($id) => (int) $id)->all();
        $submitted = $request->input('access', []);
        $insertRows = [];

        foreach ($levels as $levelId) {
            $selectedKeys = $submitted[$levelId] ?? $submitted[(string) $levelId] ?? [];
            if (! is_array($selectedKeys)) {
                continue;
            }

            foreach ($selectedKeys as $menuKey) {
                if (! in_array($menuKey, $menuKeys, true)) {
                    continue;
                }

                $insertRows[] = [
                    'levelid' => $levelId,
                    'menu_key' => $menuKey,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        DB::transaction(function () use ($insertRows): void {
            DB::table('level_sidebar_access')->delete();
            if (! empty($insertRows)) {
                DB::table('level_sidebar_access')->insert($insertRows);
            }
        });

        $this->writeActivityLog(
            $request,
            'Update Hak Akses',
            'Memperbarui pengaturan hak akses menu sidebar per level.'
        );

        return redirect()
            ->route('dashboard.hak-akses')
            ->with('status', 'Hak akses berhasil diperbarui.');
    }

    public function storeUser(Request $request): RedirectResponse
    {
        if (! $request->session()->has('auth_user')) {
            return redirect('/login')
                ->with('status', 'Silakan login terlebih dahulu.');
        }
        if (! $this->canAccessMenu($request, 'data-user')) {
            return redirect()->route('dashboard')
                ->with('status', 'Hak akses ke menu Data User tidak tersedia.');
        }

        $levelRules = ['required', 'integer'];
        if (Schema::hasTable('level') && Schema::hasColumn('level', 'levelid')) {
            $levelRules[] = Rule::exists('level', 'levelid');
        }

        $userColumns = Schema::hasTable('user') ? Schema::getColumnListing('user') : [];
        $hasUserFullName = in_array('full_name', $userColumns, true);
        $hasUserEmail = in_array('email', $userColumns, true);
        $hasUserPhone = in_array('phone_number', $userColumns, true);

        $emailRules = ['nullable', 'email', 'max:255'];
        if ($hasUserEmail) {
            $emailRules[] = Rule::unique('user', 'email');
        }

        $validated = $request->validateWithBag('createUser', [
            'username' => ['required', 'string', 'max:100', Rule::unique('user', 'username')],
            'full_name' => ['nullable', 'string', 'max:255'],
            'email' => $emailRules,
            'phone_number' => ['nullable', 'string', 'max:30'],
            'levelid' => $levelRules,
        ]);

        $defaultPassword = '12345';

        $createdUser = null;
        DB::transaction(function () use ($validated, $defaultPassword, $hasUserFullName, $hasUserEmail, $hasUserPhone, &$createdUser): void {
            $userPayload = [
                'username' => $validated['username'],
                'levelid' => (int) $validated['levelid'],
                'password' => Hash::make($defaultPassword),
                'reset_password_token' => null,
                'reset_password_token_expired' => null,
            ];

            if ($hasUserFullName) {
                $userPayload['full_name'] = $validated['full_name'] ?: null;
            }

            if ($hasUserEmail) {
                $userPayload['email'] = $validated['email'] ?: null;
            }

            if ($hasUserPhone) {
                $userPayload['phone_number'] = $validated['phone_number'] ?: null;
            }

            $user = LegacyUser::query()->create($userPayload);
            $createdUser = $user;

            if (Schema::hasTable('employer') && Schema::hasColumn('employer', 'userid')) {
                $employerColumns = Schema::getColumnListing('employer');
                $employerPayload = [
                    'userid' => (int) $user->userid,
                ];

                if (in_array('name', $employerColumns, true)) {
                    $employerPayload['name'] = $validated['full_name'] ?: $validated['username'];
                }

                if (in_array('email', $employerColumns, true)) {
                    $employerPayload['email'] = $validated['email'] ?: '';
                }

                if (in_array('phonenumber', $employerColumns, true)) {
                    $employerPayload['phonenumber'] = $validated['phone_number'] ?: '';
                }

                DB::table('employer')->insert($employerPayload);
            }
        });

        if ($createdUser instanceof LegacyUser) {
            $this->writeActivityLog(
                $request,
                'Tambah User',
                'Menambah user #' . (int) $createdUser->userid . ' username "' . $createdUser->username . '".'
            );
        }

        return redirect()
            ->route('dashboard.data-user')
            ->with('status', 'User baru berhasil ditambahkan. Password default: ' . $defaultPassword);
    }

    public function resetUserPassword(Request $request, LegacyUser $user): RedirectResponse
    {
        if (! $request->session()->has('auth_user')) {
            return redirect('/login')
                ->with('status', 'Silakan login terlebih dahulu.');
        }
        if (! $this->canAccessMenu($request, 'data-user')) {
            return redirect()->route('dashboard')
                ->with('status', 'Hak akses ke menu Data User tidak tersedia.');
        }

        $defaultPassword = '12345';
        $user->password = Hash::make($defaultPassword);
        $user->reset_password_token = null;
        $user->reset_password_token_expired = null;
        $user->save();

        $this->writeActivityLog(
            $request,
            'Reset Password User',
            'Reset password user #' . (int) $user->userid . ' username "' . $user->username . '" ke password default.'
        );

        return redirect()
            ->route('dashboard.data-user')
            ->with('status', 'Password user ' . $user->username . ' berhasil di-reset ke: ' . $defaultPassword);
    }

    public function destroyUser(Request $request, LegacyUser $user): RedirectResponse
    {
        if (! $request->session()->has('auth_user')) {
            return redirect('/login')
                ->with('status', 'Silakan login terlebih dahulu.');
        }
        if (! $this->canAccessMenu($request, 'data-user')) {
            return redirect()->route('dashboard')
                ->with('status', 'Hak akses ke menu Data User tidak tersedia.');
        }

        $authUser = $request->session()->get('auth_user');
        if ((int) ($authUser['id'] ?? 0) === (int) $user->userid) {
            return redirect()
                ->route('dashboard.data-user')
                ->with('status', 'Akun yang sedang login tidak bisa dihapus.');
        }

        $userId = (int) $user->userid;
        $username = $user->username;
        $user->delete();

        $this->writeActivityLog(
            $request,
            'Hapus User',
            'Menghapus user #' . $userId . ' username "' . $username . '".'
        );

        return redirect()
            ->route('dashboard.data-user')
            ->with('status', 'User ' . $username . ' berhasil dihapus.');
    }

    public function storeDataPlot(Request $request): JsonResponse|RedirectResponse
    {
        if (! $request->session()->has('auth_user')) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'message' => 'Silakan login terlebih dahulu.',
                ], 401);
            }

            return redirect('/login')
                ->with('status', 'Silakan login terlebih dahulu.');
        }
        if (! $this->canAccessMenu($request, 'data-plot')) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'message' => 'Hak akses ke menu Data Plot tidak tersedia.',
                ], 403);
            }

            return redirect()->route('dashboard')
                ->with('status', 'Hak akses ke menu Data Plot tidak tersedia.');
        }

        $validated = $request->validate([
            'block_id' => ['required', 'integer', 'exists:blocks,blockid'],
            'plot_number' => [
                'required',
                'string',
                'max:50',
                Rule::unique('grave_plots', 'plot_number')->where(
                    fn ($query) => $query->where('block_id', (int) $request->input('block_id'))
                ),
            ],
            'row_number' => ['nullable', 'string', 'max:50'],
            'position_x' => ['required', 'numeric', 'min:0'],
            'position_y' => ['required', 'numeric', 'min:0'],
            'width' => ['required', 'numeric', 'gt:0'],
            'height' => ['required', 'numeric', 'gt:0'],
        ]);

        $blockId = (int) $validated['block_id'];
        $maxPlotsPerBlock = 15;
        if (Schema::hasColumn('blocks', 'max_plots')) {
            $block = Block::query()
                ->select(['blockid', 'max_plots'])
                ->find($blockId);
            $maxPlotsPerBlock = max(1, (int) ($block?->max_plots ?? 15));
        }
        $existingTotalPlots = GravePlot::query()
            ->where('block_id', $blockId)
            ->count();

        if ($existingTotalPlots >= $maxPlotsPerBlock) {
            $message = 'Maksimal ' . $maxPlotsPerBlock . ' plot per blok. Tambah plot dinonaktifkan untuk blok ini.';

            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'message' => $message,
                    'errors' => [
                        'block_id' => [$message],
                    ],
                    'data' => [
                        'block_id' => $blockId,
                        'total_plots' => $existingTotalPlots,
                        'max_plots' => $maxPlotsPerBlock,
                        'is_limit_reached' => true,
                    ],
                ], 422);
            }

            return back()
                ->withInput()
                ->withErrors([
                    'block_id' => $message,
                ]);
        }

        $plot = GravePlot::query()->create([
            'block_id' => $blockId,
            'plot_number' => $validated['plot_number'],
            'row_number' => $validated['row_number'] ?: null,
            'position_x' => (float) $validated['position_x'],
            'position_y' => (float) $validated['position_y'],
            'width' => (float) $validated['width'],
            'height' => (float) $validated['height'],
            'status' => 'empty',
            'notes' => null,
        ]);

        $this->writeActivityLog(
            $request,
            'Tambah Plot',
            'Menambah plot #' . (int) $plot->plotid . ' (blok #' . $blockId . ', nomor plot "' . $plot->plot_number . '").'
        );

        if ($request->expectsJson() || $request->ajax()) {
            $totalPlots = $existingTotalPlots + 1;
            $occupiedPlots = GravePlot::query()
                ->where('block_id', $blockId)
                ->where('status', 'occupied')
                ->count();

            return response()->json([
                'message' => 'Plot berhasil ditambahkan.',
                'data' => [
                    'plotid' => (int) $plot->plotid,
                    'block_id' => $blockId,
                    'plot_number' => $plot->plot_number,
                    'row_number' => $plot->row_number,
                    'position_x' => (float) $plot->position_x,
                    'position_y' => (float) $plot->position_y,
                    'width' => (float) $plot->width,
                    'height' => (float) $plot->height,
                    'status' => 'empty',
                    'total_plots' => $totalPlots,
                    'occupied_plots' => $occupiedPlots,
                    'max_plots' => $maxPlotsPerBlock,
                    'is_limit_reached' => $totalPlots >= $maxPlotsPerBlock,
                ],
            ]);
        }

        return redirect()
            ->route('dashboard.data-plot')
            ->with('status', 'Plot berhasil ditambahkan.');
    }

    private function validateDeceasedPayload(Request $request): array
    {
        return $request->validateWithBag('almarhumForm', [
            'plotid' => ['required', 'integer', 'exists:grave_plots,plotid'],
            'full_name' => ['required', 'string', 'max:150'],
            'gender' => ['nullable', Rule::in(['male', 'female'])],
            'birth_date' => ['nullable', 'date'],
            'death_date' => ['nullable', 'date'],
            'burial_date' => ['nullable', 'date'],
            'religion' => ['nullable', 'string', 'max:50'],
            'identity_number' => ['nullable', 'string', 'max:50'],
            'address' => ['nullable', 'string', 'max:2000'],
            'description' => ['nullable', 'string', 'max:2000'],
            'photo' => ['nullable', 'file', 'image', 'max:3072'],
        ]);
    }

    private function storeDeceasedPhoto(Request $request): ?string
    {
        if (! $request->hasFile('photo')) {
            return null;
        }

        return $request->file('photo')?->store('deceased-photos', 'public');
    }

    private function resolvePhotoUrl(?string $photoPath): ?string
    {
        if (! $photoPath) {
            return null;
        }

        if (Str::startsWith($photoPath, ['http://', 'https://'])) {
            return $photoPath;
        }

        $normalizedPath = $this->normalizeStoredPhotoPath($photoPath);
        if (! $normalizedPath) {
            return null;
        }

        return route('media.deceased-photo', ['path' => $normalizedPath]);
    }

    private function normalizeStoredPhotoPath(?string $photoPath): ?string
    {
        if (! $photoPath) {
            return null;
        }

        $normalizedPath = str_replace('\\', '/', trim($photoPath));
        $normalizedPath = ltrim($normalizedPath, '/');

        if (Str::startsWith($normalizedPath, 'public/')) {
            $normalizedPath = Str::after($normalizedPath, 'public/');
        }

        if (Str::startsWith($normalizedPath, 'storage/')) {
            $normalizedPath = Str::after($normalizedPath, 'storage/');
        }

        if ($normalizedPath === '' || Str::contains($normalizedPath, '..')) {
            return null;
        }

        return $normalizedPath;
    }

    public function serveDeceasedPhoto(Request $request)
    {
        $rawPath = (string) $request->query('path', '');
        $normalizedPath = $this->normalizeStoredPhotoPath($rawPath);

        if (! $normalizedPath || ! Storage::disk('public')->exists($normalizedPath)) {
            abort(404);
        }

        return Storage::disk('public')->response($normalizedPath);
    }

    public function updateDataPlot(Request $request, GravePlot $plot): JsonResponse|RedirectResponse
    {
        if (! $request->session()->has('auth_user')) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'message' => 'Silakan login terlebih dahulu.',
                ], 401);
            }

            return redirect('/login')
                ->with('status', 'Silakan login terlebih dahulu.');
        }
        if (! $this->canAccessMenu($request, 'data-plot')) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'message' => 'Hak akses ke menu Data Plot tidak tersedia.',
                ], 403);
            }

            return redirect()->route('dashboard')
                ->with('status', 'Hak akses ke menu Data Plot tidak tersedia.');
        }

        $blockId = (int) $plot->block_id;
        $validated = $request->validate([
            'plot_number' => [
                'required',
                'string',
                'max:50',
                Rule::unique('grave_plots', 'plot_number')
                    ->where(fn ($query) => $query->where('block_id', $blockId))
                    ->ignore($plot->plotid, 'plotid'),
            ],
            'row_number' => ['nullable', 'string', 'max:50'],
            'position_x' => ['required', 'numeric', 'min:0'],
            'position_y' => ['required', 'numeric', 'min:0'],
            'width' => ['required', 'numeric', 'gt:0'],
            'height' => ['required', 'numeric', 'gt:0'],
        ]);

        $oldPlot = [
            'plot_number' => $plot->plot_number,
            'row_number' => $plot->row_number,
            'position_x' => $plot->position_x,
            'position_y' => $plot->position_y,
            'width' => $plot->width,
            'height' => $plot->height,
        ];

        $plot->fill([
            'plot_number' => $validated['plot_number'],
            'row_number' => $validated['row_number'] ?: null,
            'position_x' => (float) $validated['position_x'],
            'position_y' => (float) $validated['position_y'],
            'width' => (float) $validated['width'],
            'height' => (float) $validated['height'],
        ]);
        $plot->save();

        $plotChanges = [];
        $this->appendChangeDetail($plotChanges, 'nomor plot', $oldPlot['plot_number'], $plot->plot_number);
        $this->appendChangeDetail($plotChanges, 'baris', $oldPlot['row_number'], $plot->row_number);
        $this->appendChangeDetail($plotChanges, 'posisi X', $oldPlot['position_x'], $plot->position_x);
        $this->appendChangeDetail($plotChanges, 'posisi Y', $oldPlot['position_y'], $plot->position_y);
        $this->appendChangeDetail($plotChanges, 'lebar', $oldPlot['width'], $plot->width);
        $this->appendChangeDetail($plotChanges, 'tinggi', $oldPlot['height'], $plot->height);
        $plotDetail = 'Mengedit data plot #' . (int) $plot->plotid . '. ';
        $plotDetail .= empty($plotChanges) ? 'Tidak ada perubahan nilai.' : implode(', ', $plotChanges) . '.';
        $this->writeActivityLog($request, 'Edit Plot', $plotDetail);

        if ($request->expectsJson() || $request->ajax()) {
            $totalPlots = GravePlot::query()
                ->where('block_id', $blockId)
                ->count();
            $occupiedPlots = GravePlot::query()
                ->where('block_id', $blockId)
                ->where('status', 'occupied')
                ->count();
            $maxPlotsPerBlock = 15;
            if (Schema::hasColumn('blocks', 'max_plots')) {
                $block = Block::query()
                    ->select(['blockid', 'max_plots'])
                    ->find($blockId);
                $maxPlotsPerBlock = max(1, (int) ($block?->max_plots ?? 15));
            }

            return response()->json([
                'message' => 'Plot berhasil diperbarui.',
                'data' => [
                    'plotid' => (int) $plot->plotid,
                    'block_id' => $blockId,
                    'plot_number' => $plot->plot_number,
                    'row_number' => $plot->row_number,
                    'position_x' => (float) $plot->position_x,
                    'position_y' => (float) $plot->position_y,
                    'width' => (float) $plot->width,
                    'height' => (float) $plot->height,
                    'status' => ($plot->status ?? 'empty') === 'occupied' ? 'occupied' : 'empty',
                    'total_plots' => $totalPlots,
                    'occupied_plots' => $occupiedPlots,
                    'max_plots' => $maxPlotsPerBlock,
                    'is_limit_reached' => $totalPlots >= $maxPlotsPerBlock,
                ],
            ]);
        }

        return redirect()
            ->route('dashboard.data-plot')
            ->with('status', 'Plot berhasil diperbarui.');
    }

    public function destroyDataPlot(Request $request, GravePlot $plot): JsonResponse|RedirectResponse
    {
        if (! $request->session()->has('auth_user')) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'message' => 'Silakan login terlebih dahulu.',
                ], 401);
            }

            return redirect('/login')
                ->with('status', 'Silakan login terlebih dahulu.');
        }
        if (! $this->canAccessMenu($request, 'data-plot')) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'message' => 'Hak akses ke menu Data Plot tidak tersedia.',
                ], 403);
            }

            return redirect()->route('dashboard')
                ->with('status', 'Hak akses ke menu Data Plot tidak tersedia.');
        }

        $plotId = (int) $plot->plotid;
        $blockId = (int) $plot->block_id;
        $plotNumber = (string) ($plot->plot_number ?? '-');
        $status = (string) ($plot->status ?? 'empty');

        $hasDeceased = Deceased::query()
            ->where('plotid', $plotId)
            ->exists();

        if ($hasDeceased || $status === 'occupied') {
            $message = 'Plot tidak bisa dihapus karena masih terhubung dengan data almarhum.';
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'message' => $message,
                ], 422);
            }

            return redirect()
                ->route('dashboard.data-plot')
                ->with('status', $message);
        }

        DB::transaction(function () use ($request, $plot, $plotId, $blockId, $plotNumber): void {
            $this->archiveDeletedData(
                $request,
                'plot',
                $plotId,
                'Plot #' . $plotId . ' (blok #' . $blockId . ', nomor "' . $plotNumber . '")',
                $plot->getAttributes()
            );

            $plot->delete();
        });

        $totalPlots = GravePlot::query()
            ->where('block_id', $blockId)
            ->count();
        $occupiedPlots = GravePlot::query()
            ->where('block_id', $blockId)
            ->where('status', 'occupied')
            ->count();
        $maxPlotsPerBlock = 15;
        if (Schema::hasColumn('blocks', 'max_plots')) {
            $block = Block::query()
                ->select(['blockid', 'max_plots'])
                ->find($blockId);
            $maxPlotsPerBlock = max(1, (int) ($block?->max_plots ?? 15));
        }

        $this->writeActivityLog(
            $request,
            'Hapus Plot',
            'Menghapus data plot #' . $plotId . ' (blok #' . $blockId . ', nomor plot "' . $plotNumber . '").'
        );

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'message' => 'Plot berhasil dihapus.',
                'data' => [
                    'plotid' => $plotId,
                    'block_id' => $blockId,
                    'total_plots' => $totalPlots,
                    'occupied_plots' => $occupiedPlots,
                    'max_plots' => $maxPlotsPerBlock,
                    'is_limit_reached' => $totalPlots >= $maxPlotsPerBlock,
                ],
            ]);
        }

        return redirect()
            ->route('dashboard.data-plot')
            ->with('status', 'Plot berhasil dihapus.');
    }

    public function storeDataBlok(Request $request): JsonResponse|RedirectResponse
    {
        if (! $request->session()->has('auth_user')) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'message' => 'Silakan login terlebih dahulu.',
                ], 401);
            }

            return redirect('/login')
                ->with('status', 'Silakan login terlebih dahulu.');
        }
        if (! $this->canAccessMenu($request, 'data-blok')) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'message' => 'Hak akses ke menu Data Blok tidak tersedia.',
                ], 403);
            }

            return redirect()->route('dashboard')
                ->with('status', 'Hak akses ke menu Data Blok tidak tersedia.');
        }

        $validated = $this->validateDataBlokPayload($request);

        $createPayload = [
            'block_name' => $validated['block_name'],
            'description' => $validated['description'] ?? null,
            'map_color' => strtoupper($validated['map_color']),
        ];
        if (Schema::hasColumn('blocks', 'max_plots')) {
            $createPayload['max_plots'] = (int) ($validated['max_plots'] ?? 15);
        }
        if (Schema::hasColumn('blocks', 'map_x') && $request->has('map_x')) {
            $createPayload['map_x'] = isset($validated['map_x']) ? (int) $validated['map_x'] : null;
        }
        if (Schema::hasColumn('blocks', 'map_y') && $request->has('map_y')) {
            $createPayload['map_y'] = isset($validated['map_y']) ? (int) $validated['map_y'] : null;
        }

        $block = Block::query()->create($createPayload);

        $this->writeActivityLog(
            $request,
            'Tambah Blok',
            'Menambah data blok #' . (int) $block->blockid . ' "' . $block->block_name . '".'
        );

        $block->loadCount([
            'gravePlots as total_plots',
            'gravePlots as occupied_plots' => fn ($query) => $query->where('status', 'occupied'),
            'gravePlots as empty_plots' => fn ($query) => $query->where('status', 'empty'),
            'gravePlots as reserved_plots' => fn ($query) => $query->where('status', 'reserved'),
            'gravePlots as maintenance_plots' => fn ($query) => $query->where('status', 'maintenance'),
        ]);

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'message' => 'Data blok berhasil ditambahkan.',
                'data' => $this->formatBlockRow($block),
            ]);
        }

        return redirect()
            ->route('dashboard.data-blok')
            ->with('status', 'Data blok berhasil ditambahkan.');
    }

    public function updateDataBlok(Request $request, Block $block): JsonResponse|RedirectResponse
    {
        if (! $request->session()->has('auth_user')) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'message' => 'Silakan login terlebih dahulu.',
                ], 401);
            }

            return redirect('/login')
                ->with('status', 'Silakan login terlebih dahulu.');
        }
        if (! $this->canAccessMenu($request, 'data-blok')) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'message' => 'Hak akses ke menu Data Blok tidak tersedia.',
                ], 403);
            }

            return redirect()->route('dashboard')
                ->with('status', 'Hak akses ke menu Data Blok tidak tersedia.');
        }

        $validated = $this->validateDataBlokPayload($request);

        $updatePayload = [
            'block_name' => $validated['block_name'],
            'description' => $validated['description'] ?? null,
            'map_color' => strtoupper($validated['map_color']),
        ];
        if (Schema::hasColumn('blocks', 'max_plots')) {
            $updatePayload['max_plots'] = (int) ($validated['max_plots'] ?? 15);
        }
        if (Schema::hasColumn('blocks', 'map_x') && $request->has('map_x')) {
            $updatePayload['map_x'] = isset($validated['map_x']) ? (int) $validated['map_x'] : null;
        }
        if (Schema::hasColumn('blocks', 'map_y') && $request->has('map_y')) {
            $updatePayload['map_y'] = isset($validated['map_y']) ? (int) $validated['map_y'] : null;
        }

        $oldBlock = [
            'block_name' => $block->block_name,
            'description' => $block->description,
            'map_color' => $block->map_color,
            'max_plots' => $block->max_plots ?? null,
            'map_x' => $block->map_x ?? null,
            'map_y' => $block->map_y ?? null,
        ];

        $block->fill($updatePayload);
        $block->save();

        $blockChanges = [];
        $this->appendChangeDetail($blockChanges, 'nama blok', $oldBlock['block_name'], $block->block_name);
        $this->appendChangeDetail($blockChanges, 'deskripsi', $oldBlock['description'], $block->description);
        $this->appendChangeDetail($blockChanges, 'warna peta', $oldBlock['map_color'], $block->map_color);
        if (Schema::hasColumn('blocks', 'max_plots')) {
            $this->appendChangeDetail($blockChanges, 'maksimal plot', $oldBlock['max_plots'], $block->max_plots);
        }
        if (Schema::hasColumn('blocks', 'map_x')) {
            $this->appendChangeDetail($blockChanges, 'posisi X denah', $oldBlock['map_x'], $block->map_x);
        }
        if (Schema::hasColumn('blocks', 'map_y')) {
            $this->appendChangeDetail($blockChanges, 'posisi Y denah', $oldBlock['map_y'], $block->map_y);
        }
        $blockDetail = 'Mengedit data blok #' . (int) $block->blockid . '. ';
        $blockDetail .= empty($blockChanges) ? 'Tidak ada perubahan nilai.' : implode(', ', $blockChanges) . '.';
        $this->writeActivityLog($request, 'Edit Blok', $blockDetail);

        $block->loadCount([
            'gravePlots as total_plots',
            'gravePlots as occupied_plots' => fn ($query) => $query->where('status', 'occupied'),
            'gravePlots as empty_plots' => fn ($query) => $query->where('status', 'empty'),
            'gravePlots as reserved_plots' => fn ($query) => $query->where('status', 'reserved'),
            'gravePlots as maintenance_plots' => fn ($query) => $query->where('status', 'maintenance'),
        ]);

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'message' => 'Data blok berhasil diperbarui.',
                'data' => $this->formatBlockRow($block),
            ]);
        }

        return redirect()
            ->route('dashboard.data-blok')
            ->with('status', 'Data blok berhasil diperbarui.');
    }

    public function updateDataBlokPositions(Request $request): JsonResponse|RedirectResponse
    {
        if (! $request->session()->has('auth_user')) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'message' => 'Silakan login terlebih dahulu.',
                ], 401);
            }

            return redirect('/login')
                ->with('status', 'Silakan login terlebih dahulu.');
        }
        if (! $this->canAccessMenu($request, 'data-blok')) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'message' => 'Hak akses ke menu Data Blok tidak tersedia.',
                ], 403);
            }

            return redirect()->route('dashboard')
                ->with('status', 'Hak akses ke menu Data Blok tidak tersedia.');
        }
        if (! Schema::hasColumn('blocks', 'map_x') || ! Schema::hasColumn('blocks', 'map_y')) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'message' => 'Kolom posisi denah belum tersedia.',
                ], 422);
            }

            return redirect()
                ->route('dashboard')
                ->with('status', 'Kolom posisi denah belum tersedia.');
        }

        $rules = [
            'blocks' => ['required', 'array', 'min:1'],
            'blocks.*.id' => ['required', 'integer', 'exists:blocks,blockid'],
            'blocks.*.x' => ['required', 'integer'],
            'blocks.*.y' => ['required', 'integer'],
        ];

        $facilityMapStorage = $this->resolveFacilityMapStorage();
        if (Schema::hasTable('facility') && $facilityMapStorage !== 'none') {
            $rules['facility_items'] = ['nullable', 'array'];
            $rules['facility_items.*.id'] = $facilityMapStorage === 'place'
                ? ['nullable', 'integer', 'exists:place,placeid']
                : ['nullable', 'integer', 'exists:facility_map_items,facility_map_itemid'];
            $rules['facility_items.*.facility_id'] = ['nullable', 'integer', 'min:0'];
            $rules['facility_items.*.item_type'] = ['nullable', 'string', Rule::in(['icon', 'scene'])];
            $rules['facility_items.*.scene_object_key'] = ['nullable', 'string', 'max:120'];
            $rules['facility_items.*.x'] = ['required', 'integer'];
            $rules['facility_items.*.y'] = ['required', 'integer'];
            $rules['facility_items.*.width'] = ['nullable', 'integer', 'min:1', 'max:3000'];
            $rules['facility_items.*.height'] = ['nullable', 'integer', 'min:1', 'max:3000'];
            $rules['facility_items.*.rotation'] = ['nullable', 'numeric', 'min:-360', 'max:360'];
            $rules['facility_items.*.is_removed'] = ['nullable', 'boolean'];
            $rules['facility_items.*.is_fixed'] = ['nullable', 'boolean'];
        }

        $validated = $request->validate($rules);

        $incomingPositions = collect($validated['blocks'])
            ->map(fn (array $item) => [
                'id' => (int) ($item['id'] ?? 0),
                'x' => (int) ($item['x'] ?? 0),
                'y' => (int) ($item['y'] ?? 0),
            ])
            ->unique('id')
            ->values();

        $targetBlockIds = $incomingPositions->pluck('id')->values()->all();
        $targetBlocks = Block::query()
            ->select(['blockid', 'block_name', 'map_x', 'map_y'])
            ->whereIn('blockid', $targetBlockIds)
            ->get()
            ->keyBy(fn (Block $block) => (int) $block->blockid);
        $oldPositionById = $targetBlocks->mapWithKeys(fn (Block $block) => [
            (int) $block->blockid => [
                'x' => isset($block->map_x) ? (int) $block->map_x : null,
                'y' => isset($block->map_y) ? (int) $block->map_y : null,
            ],
        ]);

        $facilityItemsPayload = collect($validated['facility_items'] ?? [])
            ->map(fn (array $item) => [
                'id' => isset($item['id']) ? (int) $item['id'] : null,
                'facility_id' => (int) ($item['facility_id'] ?? 0),
                'item_type' => in_array((string) ($item['item_type'] ?? 'icon'), ['icon', 'scene'], true)
                    ? (string) ($item['item_type'] ?? 'icon')
                    : 'icon',
                'scene_object_key' => isset($item['scene_object_key']) ? trim((string) $item['scene_object_key']) : null,
                'x' => (int) ($item['x'] ?? 0),
                'y' => (int) ($item['y'] ?? 0),
                'width' => isset($item['width']) && (int) $item['width'] > 0 ? (int) $item['width'] : null,
                'height' => isset($item['height']) && (int) $item['height'] > 0 ? (int) $item['height'] : null,
                'rotation' => isset($item['rotation']) ? (float) $item['rotation'] : null,
                'is_removed' => (bool) ($item['is_removed'] ?? false),
                'is_fixed' => (bool) ($item['is_fixed'] ?? false),
            ])
            ->filter(function (array $item): bool {
                if (! $item['is_fixed']) {
                    return false;
                }

                if (($item['item_type'] ?? 'icon') === 'scene') {
                    return ($item['scene_object_key'] ?? null) !== null;
                }

                return $item['facility_id'] > 0;
            })
            ->values();

        $facilityChanges = [];
        $savedFacilityItemStates = [];
        $savedSceneItemStates = [];
        $hasPlaceWidth = $facilityMapStorage === 'place' && Schema::hasColumn('place', 'map_width');
        $hasPlaceHeight = $facilityMapStorage === 'place' && Schema::hasColumn('place', 'map_height');
        $hasPlaceRotation = $facilityMapStorage === 'place' && Schema::hasColumn('place', 'map_rotation');
        $hasPlaceItemType = $facilityMapStorage === 'place' && Schema::hasColumn('place', 'item_type');
        $hasPlaceSceneKey = $facilityMapStorage === 'place' && Schema::hasColumn('place', 'scene_object_key');
        $hasPlaceIsRemoved = $facilityMapStorage === 'place' && Schema::hasColumn('place', 'is_removed');
        $facilityMapItemGeometry = $this->facilityMapItemGeometryColumns();
        $facilityMapItemSceneColumns = $this->facilityMapItemSceneColumns();
        $hasFacilityMapItemWidth = $facilityMapStorage === 'facility_map_items' && $facilityMapItemGeometry['width'];
        $hasFacilityMapItemHeight = $facilityMapStorage === 'facility_map_items' && $facilityMapItemGeometry['height'];
        $hasFacilityMapItemRotation = $facilityMapStorage === 'facility_map_items' && $facilityMapItemGeometry['rotation'];
        $hasFacilityMapItemType = $facilityMapStorage === 'facility_map_items' && $facilityMapItemSceneColumns['item_type'];
        $hasFacilityMapItemSceneKey = $facilityMapStorage === 'facility_map_items' && $facilityMapItemSceneColumns['scene_object_key'];
        $hasFacilityMapItemIsRemoved = $facilityMapStorage === 'facility_map_items' && $facilityMapItemSceneColumns['is_removed'];

        DB::transaction(function () use ($incomingPositions, $targetBlocks, $facilityItemsPayload, $facilityMapStorage, $hasPlaceWidth, $hasPlaceHeight, $hasPlaceRotation, $hasPlaceItemType, $hasPlaceSceneKey, $hasPlaceIsRemoved, $hasFacilityMapItemWidth, $hasFacilityMapItemHeight, $hasFacilityMapItemRotation, $hasFacilityMapItemType, $hasFacilityMapItemSceneKey, $hasFacilityMapItemIsRemoved, &$facilityChanges, &$savedFacilityItemStates, &$savedSceneItemStates): void {
            foreach ($incomingPositions as $position) {
                /** @var Block|null $targetBlock */
                $targetBlock = $targetBlocks->get((int) $position['id']);
                if (! $targetBlock) {
                    continue;
                }

                $targetBlock->map_x = (int) $position['x'];
                $targetBlock->map_y = (int) $position['y'];
                $targetBlock->save();
            }

            if (! Schema::hasTable('facility') || $facilityMapStorage === 'none') {
                return;
            }

            if ($facilityMapStorage === 'facility_map_items') {
                $fixedFacilityItems = $facilityItemsPayload
                    ->filter(fn (array $item): bool => ($item['item_type'] ?? 'icon') === 'icon')
                    ->values();
                $sceneItems = $facilityItemsPayload
                    ->filter(fn (array $item): bool => ($item['item_type'] ?? 'icon') === 'scene')
                    ->values();

                $incomingIds = $fixedFacilityItems
                    ->pluck('id')
                    ->filter(fn ($id): bool => is_int($id) && $id > 0)
                    ->values()
                    ->all();

                $fixedItemsQuery = DB::table('facility_map_items')
                    ->where('is_fixed', true);
                if ($hasFacilityMapItemType) {
                    $fixedItemsQuery->where(function ($query): void {
                        $query->whereNull('item_type')
                            ->orWhere('item_type', '')
                            ->orWhere('item_type', 'icon');
                    });
                }

                if (! empty($incomingIds)) {
                    $fixedItemsQuery
                        ->whereNotIn('facility_map_itemid', $incomingIds)
                        ->delete();
                } else {
                    $fixedItemsQuery->delete();
                }

                if ($hasFacilityMapItemType && $hasFacilityMapItemSceneKey) {
                    $incomingSceneKeys = $sceneItems
                        ->pluck('scene_object_key')
                        ->filter(fn ($value): bool => is_string($value) && $value !== '')
                        ->values()
                        ->all();

                    $sceneCleanupQuery = DB::table('facility_map_items')
                        ->where('is_fixed', true)
                        ->where('item_type', 'scene');

                    if (! empty($incomingSceneKeys)) {
                        $sceneCleanupQuery
                            ->whereNotIn('scene_object_key', $incomingSceneKeys)
                            ->delete();
                    } else {
                        $sceneCleanupQuery->delete();
                    }
                }

                $timestamp = now();

                foreach ($fixedFacilityItems as $item) {
                    $target = null;
                    $oldX = null;
                    $oldY = null;
                    $oldFacilityId = null;

                    if (! empty($item['id'])) {
                        $targetQuery = DB::table('facility_map_items')
                            ->where('facility_map_itemid', (int) $item['id'])
                            ->where('is_fixed', true);
                        if ($hasFacilityMapItemType) {
                            $targetQuery->where(function ($query): void {
                                $query->whereNull('item_type')
                                    ->orWhere('item_type', '')
                                    ->orWhere('item_type', 'icon');
                            });
                        }
                        $target = $targetQuery->first();
                    }

                    if (! $target) {
                        $insertPayload = [
                            'facility_id' => (int) $item['facility_id'],
                            'map_x' => (int) $item['x'],
                            'map_y' => (int) $item['y'],
                            'is_fixed' => true,
                            'created_at' => $timestamp,
                            'updated_at' => $timestamp,
                        ];
                        if ($hasFacilityMapItemWidth && $item['width'] !== null) {
                            $insertPayload['map_width'] = (int) $item['width'];
                        }
                        if ($hasFacilityMapItemHeight && $item['height'] !== null) {
                            $insertPayload['map_height'] = (int) $item['height'];
                        }
                        if ($hasFacilityMapItemRotation && $item['rotation'] !== null) {
                            $insertPayload['map_rotation'] = (float) $item['rotation'];
                        }
                        if ($hasFacilityMapItemType) {
                            $insertPayload['item_type'] = 'icon';
                        }
                        if ($hasFacilityMapItemSceneKey) {
                            $insertPayload['scene_object_key'] = null;
                        }
                        if ($hasFacilityMapItemIsRemoved) {
                            $insertPayload['is_removed'] = false;
                        }

                        DB::table('facility_map_items')->insert($insertPayload);

                        $newId = (int) DB::getPdo()->lastInsertId();
                        $savedFacilityItemStates[] = $this->makePersistedFacilityItemState($item, $newId);
                        $facilityChanges[] = '#' . $newId
                            . ' f' . (int) $item['facility_id']
                            . ' (X:' . (int) $item['x']
                            . ', Y:' . (int) $item['y'] . ')';
                        continue;
                    }

                    $oldX = isset($target->map_x) ? (int) $target->map_x : null;
                    $oldY = isset($target->map_y) ? (int) $target->map_y : null;
                    $oldFacilityId = isset($target->facility_id) ? (int) $target->facility_id : null;

                    $updatePayload = [
                        'facility_id' => (int) $item['facility_id'],
                        'map_x' => (int) $item['x'],
                        'map_y' => (int) $item['y'],
                        'is_fixed' => true,
                        'updated_at' => $timestamp,
                    ];
                    if ($hasFacilityMapItemWidth) {
                        $updatePayload['map_width'] = $item['width'] !== null ? (int) $item['width'] : null;
                    }
                    if ($hasFacilityMapItemHeight) {
                        $updatePayload['map_height'] = $item['height'] !== null ? (int) $item['height'] : null;
                    }
                    if ($hasFacilityMapItemRotation) {
                        $updatePayload['map_rotation'] = $item['rotation'] !== null ? (float) $item['rotation'] : null;
                    }
                    if ($hasFacilityMapItemType) {
                        $updatePayload['item_type'] = 'icon';
                    }
                    if ($hasFacilityMapItemSceneKey) {
                        $updatePayload['scene_object_key'] = null;
                    }
                    if ($hasFacilityMapItemIsRemoved) {
                        $updatePayload['is_removed'] = false;
                    }

                    DB::table('facility_map_items')
                        ->where('facility_map_itemid', (int) $item['id'])
                        ->update($updatePayload);

                    $savedFacilityItemStates[] = $this->makePersistedFacilityItemState($item, (int) $item['id']);
                    if ($oldX !== (int) $item['x'] || $oldY !== (int) $item['y'] || $oldFacilityId !== (int) $item['facility_id']) {
                        $facilityChanges[] = '#' . (int) $item['id']
                            . ' f' . (int) $item['facility_id']
                            . ' (X:' . $this->logValue($oldX) . '->' . (int) $item['x']
                            . ', Y:' . $this->logValue($oldY) . '->' . (int) $item['y'] . ')';
                    }
                }

                if (! $hasFacilityMapItemType || ! $hasFacilityMapItemSceneKey) {
                    return;
                }

                foreach ($sceneItems as $item) {
                    $sceneObjectKey = (string) ($item['scene_object_key'] ?? '');
                    if ($sceneObjectKey === '') {
                        continue;
                    }

                    $target = DB::table('facility_map_items')
                        ->where('is_fixed', true)
                        ->where('item_type', 'scene')
                        ->where('scene_object_key', $sceneObjectKey)
                        ->first();

                    $oldX = isset($target->map_x) ? (int) $target->map_x : null;
                    $oldY = isset($target->map_y) ? (int) $target->map_y : null;
                    $oldFacilityId = isset($target->facility_id) ? (int) $target->facility_id : null;
                    $oldRemoved = isset($target->is_removed) ? (bool) $target->is_removed : null;

                    if (! $target) {
                        $insertPayload = [
                            'facility_id' => max(0, (int) ($item['facility_id'] ?? 0)),
                            'item_type' => 'scene',
                            'scene_object_key' => $sceneObjectKey,
                            'map_x' => (int) $item['x'],
                            'map_y' => (int) $item['y'],
                            'is_fixed' => true,
                            'created_at' => $timestamp,
                            'updated_at' => $timestamp,
                        ];
                        if ($hasFacilityMapItemWidth && $item['width'] !== null) {
                            $insertPayload['map_width'] = (int) $item['width'];
                        }
                        if ($hasFacilityMapItemHeight && $item['height'] !== null) {
                            $insertPayload['map_height'] = (int) $item['height'];
                        }
                        if ($hasFacilityMapItemRotation && $item['rotation'] !== null) {
                            $insertPayload['map_rotation'] = (float) $item['rotation'];
                        }
                        if ($hasFacilityMapItemIsRemoved) {
                            $insertPayload['is_removed'] = (bool) ($item['is_removed'] ?? false);
                        }

                        DB::table('facility_map_items')->insert($insertPayload);

                        $newId = (int) DB::getPdo()->lastInsertId();
                        $savedSceneItemStates[] = $this->makePersistedSceneItemState($item, $newId);
                        $facilityChanges[] = 'scene:' . $sceneObjectKey
                            . ' (X:' . (int) $item['x']
                            . ', Y:' . (int) $item['y'] . ')';
                        continue;
                    }

                    $updatePayload = [
                        'facility_id' => max(0, (int) ($item['facility_id'] ?? 0)),
                        'item_type' => 'scene',
                        'scene_object_key' => $sceneObjectKey,
                        'map_x' => (int) $item['x'],
                        'map_y' => (int) $item['y'],
                        'is_fixed' => true,
                        'updated_at' => $timestamp,
                    ];
                    if ($hasFacilityMapItemWidth) {
                        $updatePayload['map_width'] = $item['width'] !== null ? (int) $item['width'] : null;
                    }
                    if ($hasFacilityMapItemHeight) {
                        $updatePayload['map_height'] = $item['height'] !== null ? (int) $item['height'] : null;
                    }
                    if ($hasFacilityMapItemRotation) {
                        $updatePayload['map_rotation'] = $item['rotation'] !== null ? (float) $item['rotation'] : null;
                    }
                    if ($hasFacilityMapItemIsRemoved) {
                        $updatePayload['is_removed'] = (bool) ($item['is_removed'] ?? false);
                    }

                    DB::table('facility_map_items')
                        ->where('facility_map_itemid', (int) $target->facility_map_itemid)
                        ->update($updatePayload);

                    $savedSceneItemStates[] = $this->makePersistedSceneItemState($item, (int) $target->facility_map_itemid);
                    $isRemoved = (bool) ($item['is_removed'] ?? false);
                    if (
                        $oldX !== (int) $item['x']
                        || $oldY !== (int) $item['y']
                        || $oldFacilityId !== max(0, (int) ($item['facility_id'] ?? 0))
                        || ($hasFacilityMapItemIsRemoved && $oldRemoved !== $isRemoved)
                    ) {
                        $facilityChanges[] = 'scene:' . $sceneObjectKey
                            . ' (X:' . $this->logValue($oldX) . '->' . (int) $item['x']
                            . ', Y:' . $this->logValue($oldY) . '->' . (int) $item['y'] . ')';
                    }
                }

                return;
            }

            $iconItems = $facilityItemsPayload
                ->filter(fn (array $item): bool => ($item['item_type'] ?? 'icon') === 'icon')
                ->values();
            $sceneItems = $facilityItemsPayload
                ->filter(fn (array $item): bool => ($item['item_type'] ?? 'icon') === 'scene')
                ->values();

            $incomingIds = $iconItems
                ->pluck('id')
                ->filter(fn ($id): bool => is_int($id) && $id > 0)
                ->values()
                ->all();

            $placeIconsQuery = DB::table('place');
            if ($hasPlaceItemType) {
                $placeIconsQuery->where(function ($query): void {
                    $query->whereNull('item_type')
                        ->orWhere('item_type', '')
                        ->orWhere('item_type', 'icon');
                });
            }

            if (! empty($incomingIds)) {
                $placeIconsQuery
                    ->whereNotIn('placeid', $incomingIds)
                    ->delete();
            } else {
                $placeIconsQuery->delete();
            }

            if ($hasPlaceItemType && $hasPlaceSceneKey) {
                $incomingSceneKeys = $sceneItems
                    ->pluck('scene_object_key')
                    ->filter(fn ($value): bool => is_string($value) && $value !== '')
                    ->values()
                    ->all();

                $placeSceneQuery = DB::table('place')
                    ->where('item_type', 'scene');

                if (! empty($incomingSceneKeys)) {
                    $placeSceneQuery
                        ->whereNotIn('scene_object_key', $incomingSceneKeys)
                        ->delete();
                } else {
                    $placeSceneQuery->delete();
                }
            }

            foreach ($facilityItemsPayload as $item) {
                $target = null;
                $oldX = null;
                $oldY = null;
                $oldFacilityId = null;

                if (! empty($item['id'])) {
                    $target = DB::table('place')
                        ->where('placeid', (int) $item['id'])
                        ->first();
                } elseif (
                    ($item['item_type'] ?? 'icon') === 'scene'
                    && $hasPlaceItemType
                    && $hasPlaceSceneKey
                    && ($item['scene_object_key'] ?? null) !== null
                ) {
                    $target = DB::table('place')
                        ->where('item_type', 'scene')
                        ->where('scene_object_key', (string) $item['scene_object_key'])
                        ->first();
                }

                if (! $target) {
                    $insertPayload = [
                        'facilityid' => (int) $item['facility_id'],
                        'x' => (int) $item['x'],
                        'y' => (int) $item['y'],
                    ];
                    if ($hasPlaceItemType) {
                        $insertPayload['item_type'] = (string) ($item['item_type'] ?? 'icon');
                    }
                    if ($hasPlaceSceneKey) {
                        $insertPayload['scene_object_key'] = ($item['scene_object_key'] ?? '') !== ''
                            ? (string) $item['scene_object_key']
                            : null;
                    }
                    if ($hasPlaceIsRemoved) {
                        $insertPayload['is_removed'] = (bool) ($item['is_removed'] ?? false);
                    }
                    if ($hasPlaceWidth && $item['width'] !== null) {
                        $insertPayload['map_width'] = (int) $item['width'];
                    }
                    if ($hasPlaceHeight && $item['height'] !== null) {
                        $insertPayload['map_height'] = (int) $item['height'];
                    }
                    if ($hasPlaceRotation && $item['rotation'] !== null) {
                        $insertPayload['map_rotation'] = (float) $item['rotation'];
                    }

                    DB::table('place')->insert($insertPayload);

                    $newId = (int) DB::getPdo()->lastInsertId();
                    if (($item['item_type'] ?? 'icon') === 'icon') {
                        $savedFacilityItemStates[] = $this->makePersistedFacilityItemState($item, $newId);
                        $facilityChanges[] = '#' . $newId
                            . ' f' . (int) $item['facility_id']
                            . ' (X:' . (int) $item['x']
                            . ', Y:' . (int) $item['y'] . ')';
                    } else {
                        $savedSceneItemStates[] = $this->makePersistedSceneItemState($item, $newId);
                        $facilityChanges[] = 'scene:' . (string) ($item['scene_object_key'] ?? '-')
                            . ' (X:' . (int) $item['x']
                            . ', Y:' . (int) $item['y'] . ')';
                    }
                } else {
                    $oldX = isset($target->x) ? (int) $target->x : null;
                    $oldY = isset($target->y) ? (int) $target->y : null;
                    $oldFacilityId = isset($target->facilityid) ? (int) $target->facilityid : null;
                    $oldRemoved = isset($target->is_removed) ? (bool) $target->is_removed : null;

                    $updatePayload = [
                        'facilityid' => (int) $item['facility_id'],
                        'x' => (int) $item['x'],
                        'y' => (int) $item['y'],
                    ];
                    if ($hasPlaceItemType) {
                        $updatePayload['item_type'] = (string) ($item['item_type'] ?? 'icon');
                    }
                    if ($hasPlaceSceneKey) {
                        $updatePayload['scene_object_key'] = ($item['scene_object_key'] ?? '') !== ''
                            ? (string) $item['scene_object_key']
                            : null;
                    }
                    if ($hasPlaceIsRemoved) {
                        $updatePayload['is_removed'] = (bool) ($item['is_removed'] ?? false);
                    }
                    if ($hasPlaceWidth && $item['width'] !== null) {
                        $updatePayload['map_width'] = (int) $item['width'];
                    }
                    if ($hasPlaceHeight && $item['height'] !== null) {
                        $updatePayload['map_height'] = (int) $item['height'];
                    }
                    if ($hasPlaceRotation && $item['rotation'] !== null) {
                        $updatePayload['map_rotation'] = (float) $item['rotation'];
                    }

                    DB::table('place')
                        ->where('placeid', (int) $target->placeid)
                        ->update($updatePayload);

                    if (($item['item_type'] ?? 'icon') === 'icon') {
                        $savedFacilityItemStates[] = $this->makePersistedFacilityItemState($item, (int) $target->placeid);
                        if ($oldX !== (int) $item['x'] || $oldY !== (int) $item['y'] || $oldFacilityId !== (int) $item['facility_id']) {
                            $facilityChanges[] = '#' . (int) $target->placeid
                                . ' f' . (int) $item['facility_id']
                                . ' (X:' . $this->logValue($oldX) . '->' . (int) $item['x']
                                . ', Y:' . $this->logValue($oldY) . '->' . (int) $item['y'] . ')';
                        }
                    } else {
                        $savedSceneItemStates[] = $this->makePersistedSceneItemState($item, (int) $target->placeid);
                        $isRemoved = (bool) ($item['is_removed'] ?? false);
                        if (
                            $oldX !== (int) $item['x']
                            || $oldY !== (int) $item['y']
                            || $oldFacilityId !== (int) $item['facility_id']
                            || ($hasPlaceIsRemoved && $oldRemoved !== $isRemoved)
                        ) {
                            $facilityChanges[] = 'scene:' . (string) ($item['scene_object_key'] ?? '-')
                                . ' (X:' . $this->logValue($oldX) . '->' . (int) $item['x']
                                . ', Y:' . $this->logValue($oldY) . '->' . (int) $item['y'] . ')';
                        }
                    }
                }
            }
        });

        $changes = [];
        foreach ($incomingPositions as $position) {
            /** @var Block|null $targetBlock */
            $targetBlock = $targetBlocks->get((int) $position['id']);
            if (! $targetBlock) {
                continue;
            }

            $oldPosition = $oldPositionById->get((int) $position['id'], ['x' => null, 'y' => null]);
            $oldX = $oldPosition['x'];
            $oldY = $oldPosition['y'];
            $newX = (int) $position['x'];
            $newY = (int) $position['y'];

            if ($oldX === $newX && $oldY === $newY) {
                continue;
            }

            $changes[] = $targetBlock->block_name . ' (X:' . $this->logValue($oldX) . '->' . $newX . ', Y:' . $this->logValue($oldY) . '->' . $newY . ')';
        }

        $detail = 'Mengatur posisi denah blok dari dashboard. ';
        if (empty($changes) && empty($facilityChanges)) {
            $detail .= 'Tidak ada perubahan nilai.';
        } else {
            $parts = [];
            if (! empty($changes)) {
                $parts[] = 'Perubahan blok (' . count($changes) . '): ' . $this->summarizeLogItems($changes, 6, 'blok');
            }
            if (! empty($facilityChanges)) {
                $parts[] = 'Perubahan fasilitas (' . count($facilityChanges) . '): ' . $this->summarizeLogItems($facilityChanges, 8, 'item');
            }
            $detail .= implode(' | ', $parts) . '.';
        }
        $this->writeActivityLog($request, 'Edit Posisi Blok', $detail);

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'message' => 'Posisi denah blok berhasil disimpan.',
                'facility_map_storage' => $facilityMapStorage,
                'facility_map_shape_persisted' => $this->facilityMapShapePersisted($facilityMapStorage),
                'facility_item_states' => $savedFacilityItemStates,
                'scene_item_states' => $savedSceneItemStates,
            ]);
        }

        return redirect()
            ->route('dashboard')
            ->with('status', 'Posisi denah blok berhasil disimpan.');
    }

    public function destroyDataBlok(Request $request, Block $block): JsonResponse|RedirectResponse
    {
        if (! $request->session()->has('auth_user')) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'message' => 'Silakan login terlebih dahulu.',
                ], 401);
            }

            return redirect('/login')
                ->with('status', 'Silakan login terlebih dahulu.');
        }
        if (! $this->canAccessMenu($request, 'data-blok')) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'message' => 'Hak akses ke menu Data Blok tidak tersedia.',
                ], 403);
            }

            return redirect()->route('dashboard')
                ->with('status', 'Hak akses ke menu Data Blok tidak tersedia.');
        }

        $blockId = (int) $block->blockid;
        $blockName = (string) $block->block_name;
        $totalPlots = GravePlot::query()
            ->where('block_id', $block->blockid)
            ->count();

        if ($totalPlots > 0) {
            return response()->json([
                'message' => 'Blok tidak bisa dihapus karena masih memiliki data petak.',
            ], 422);
        }

        DB::transaction(function () use ($request, $block, $blockId, $blockName): void {
            $this->archiveDeletedData(
                $request,
                'block',
                $blockId,
                'Blok #' . $blockId . ' "' . $blockName . '"',
                $block->getAttributes()
            );

            $block->delete();
        });

        $this->writeActivityLog(
            $request,
            'Hapus Blok',
            'Menghapus data blok #' . $blockId . ' "' . $blockName . '".'
        );

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'message' => 'Data blok berhasil dihapus.',
            ]);
        }

        return redirect()
            ->route('dashboard.data-blok')
            ->with('status', 'Data blok berhasil dihapus.');
    }

    public function websiteSettings(Request $request): View|RedirectResponse
    {
        if (! $request->session()->has('auth_user')) {
            return redirect('/login')
                ->with('status', 'Silakan login terlebih dahulu.');
        }
        if (! $this->canAccessMenu($request, 'settings')) {
            return redirect()->route('dashboard')
                ->with('status', 'Hak akses ke menu Pengaturan tidak tersedia.');
        }

        $setting = $this->resolveSystemSetting();

        return view('website-settings', [
            'authUser' => $request->session()->get('auth_user'),
            'setting' => $setting,
        ]);
    }

    public function updateWebsiteSettings(Request $request): RedirectResponse
    {
        if (! $request->session()->has('auth_user')) {
            return redirect('/login')
                ->with('status', 'Silakan login terlebih dahulu.');
        }
        if (! $this->canAccessMenu($request, 'settings')) {
            return redirect()->route('dashboard')
                ->with('status', 'Hak akses ke menu Pengaturan tidak tersedia.');
        }

        $validated = $request->validate([
            'website_name' => ['required', 'string', 'max:255'],
            'address' => ['nullable', 'string', 'max:255'],
            'contact' => ['nullable', 'string', 'max:255'],
            'manager' => ['nullable', 'string', 'max:255'],
            'logo_path' => ['nullable', 'string', 'max:255'],
            'logo_file' => ['nullable', 'file', 'mimes:jpg,jpeg,png,webp,svg', 'max:2048'],
        ]);

        $setting = SystemSetting::query()->first();

        if (! $setting) {
            $setting = new SystemSetting();
        }

        $setting->systemname = $validated['website_name'];
        $setting->systemaddress = $validated['address'] ?? null;
        $setting->systemcontact = $validated['contact'] ?? null;
        $setting->systemmanager = $validated['manager'] ?? null;

        if (! empty($validated['logo_path'])) {
            $setting->systemlogo = $validated['logo_path'];
        }

        if ($request->hasFile('logo_file')) {
            $uploaded = $request->file('logo_file');
            $filename = 'logo-' . now()->format('YmdHis') . '-' . Str::random(6) . '.' . $uploaded->getClientOriginalExtension();
            $path = $uploaded->storeAs('system-logos', $filename, 'public');
            $setting->systemlogo = 'storage/' . $path;
        }

        $setting->save();

        return redirect()
            ->route('dashboard.settings')
            ->with('status', 'Pengaturan website berhasil disimpan.');
    }

    public function accountSettings(Request $request): View|RedirectResponse
    {
        if (! $request->session()->has('auth_user')) {
            return redirect('/login')
                ->with('status', 'Silakan login terlebih dahulu.');
        }

        $authUser = $request->session()->get('auth_user');
        $user = LegacyUser::query()->find($authUser['id'] ?? 0);

        if (! $user) {
            $request->session()->forget('auth_user');

            return redirect('/login')
                ->with('status', 'Sesi tidak valid. Silakan login kembali.');
        }

        $userColumns = Schema::hasTable('user') ? Schema::getColumnListing('user') : [];
        $resolveColumn = static function (array $columns, array $candidates): ?string {
            foreach ($candidates as $candidate) {
                if (in_array($candidate, $columns, true)) {
                    return $candidate;
                }
            }

            return null;
        };
        $firstFilledValue = static function ($record, array $columns): ?string {
            if (! $record) {
                return null;
            }

            foreach ($columns as $column) {
                if (isset($record->{$column}) && trim((string) $record->{$column}) !== '') {
                    return trim((string) $record->{$column});
                }
            }

            return null;
        };

        $fullName = $firstFilledValue($user, [
            $resolveColumn($userColumns, ['full_name', 'name', 'fullname', 'employee_name']) ?? 'full_name',
            'username',
        ]) ?? (string) $user->username;
        $email = $firstFilledValue($user, [
            $resolveColumn($userColumns, ['email', 'email_address', 'mail']) ?? 'email',
        ]);
        $phoneNumber = $firstFilledValue($user, [
            $resolveColumn($userColumns, ['phone_number', 'phonenumber', 'phone', 'phone_no', 'no_hp']) ?? 'phone_number',
        ]);

        if (Schema::hasTable('employer') && Schema::hasColumn('employer', 'userid')) {
            $employer = DB::table('employer')
                ->where('userid', $user->userid)
                ->first();

            if ($employer) {
                $employerColumns = Schema::getColumnListing('employer');

                $fullName = $firstFilledValue($employer, [
                    $resolveColumn($employerColumns, ['full_name', 'name', 'fullname', 'employee_name']) ?? 'name',
                ]) ?? $fullName;
                $email = $firstFilledValue($employer, [
                    $resolveColumn($employerColumns, ['email', 'email_address', 'mail']) ?? 'email',
                ]) ?? $email;
                $phoneNumber = $firstFilledValue($employer, [
                    $resolveColumn($employerColumns, ['phone_number', 'phonenumber', 'phone', 'phone_no', 'no_hp']) ?? 'phonenumber',
                ]) ?? $phoneNumber;
            }
        }

        return view('account-settings', [
            'authUser' => $authUser,
            'userAccount' => $user,
            'accountProfile' => [
                'full_name' => $fullName,
                'email' => $email,
                'phone_number' => $phoneNumber,
            ],
        ]);
    }

    public function updateAccountSettings(Request $request): RedirectResponse
    {
        if (! $request->session()->has('auth_user')) {
            return redirect('/login')
                ->with('status', 'Silakan login terlebih dahulu.');
        }

        $authUser = $request->session()->get('auth_user');
        $user = LegacyUser::query()->find($authUser['id'] ?? 0);

        if (! $user) {
            $request->session()->forget('auth_user');

            return redirect('/login')
                ->with('status', 'Sesi tidak valid. Silakan login kembali.');
        }

        $validated = $request->validateWithBag('accountUpdate', [
            'full_name' => ['required', 'string', 'max:255'],
            'phone_number' => ['nullable', 'string', 'max:30'],
            'email' => ['nullable', 'email', 'max:255'],
        ]);

        $oldProfile = [
            'full_name' => $user->full_name,
            'phone_number' => $user->phone_number,
            'email' => $user->email,
        ];

        $user->full_name = $validated['full_name'];
        $user->phone_number = $validated['phone_number'] ?: null;
        $user->email = $validated['email'] ?: null;
        $user->save();

        $profileChanges = [];
        $this->appendChangeDetail($profileChanges, 'nama', $oldProfile['full_name'], $user->full_name);
        $this->appendChangeDetail($profileChanges, 'telepon', $oldProfile['phone_number'], $user->phone_number);
        $this->appendChangeDetail($profileChanges, 'email', $oldProfile['email'], $user->email);

        $request->session()->put('auth_user', [
            'id' => (int) $user->userid,
            'username' => $user->username,
            'levelid' => (int) $user->levelid,
            'name' => $user->full_name ?: $user->username,
            'latitude' => $authUser['latitude'] ?? null,
            'longitude' => $authUser['longitude'] ?? null,
        ]);

        $profileDetail = 'Mengedit profil akun. ';
        $profileDetail .= empty($profileChanges) ? 'Tidak ada perubahan nilai.' : implode(', ', $profileChanges) . '.';
        $this->writeActivityLog($request, 'Edit Profil', $profileDetail);

        return redirect()
            ->route('dashboard.account')
            ->with('account_status', 'Data akun berhasil diperbarui.');
    }

    public function updateAccountPassword(Request $request): RedirectResponse
    {
        if (! $request->session()->has('auth_user')) {
            return redirect('/login')
                ->with('status', 'Silakan login terlebih dahulu.');
        }

        $authUser = $request->session()->get('auth_user');
        $user = LegacyUser::query()->find($authUser['id'] ?? 0);

        if (! $user) {
            $request->session()->forget('auth_user');

            return redirect('/login')
                ->with('status', 'Sesi tidak valid. Silakan login kembali.');
        }

        $validated = $request->validateWithBag('passwordUpdate', [
            'current_password' => ['required', 'string'],
            'new_password' => ['required', 'string', 'min:6', 'confirmed'],
        ]);

        $validPassword = Hash::check($validated['current_password'], (string) $user->password)
            || hash_equals((string) $user->password, $validated['current_password']);

        if (! $validPassword) {
            return back()->withErrors([
                'current_password' => 'Password saat ini tidak sesuai.',
            ], 'passwordUpdate');
        }

        $user->password = Hash::make($validated['new_password']);
        $user->save();

        $this->writeActivityLog(
            $request,
            'Ubah Password',
            'Mengubah password akun sendiri.'
        );

        return redirect()
            ->route('dashboard.account')
            ->with('password_status', 'Password berhasil diubah.');
    }

    private function writeActivityLog(Request $request, string $action, string $detail, array $overrides = []): void
    {
        if (! Schema::hasTable('activity_logs')) {
            return;
        }

        $actor = $this->resolveAuthActor($request);
        $payload = array_merge($actor, $overrides);

        DB::table('activity_logs')->insert([
            'user_id' => $payload['id'] ?? null,
            'name' => Str::limit((string) ($payload['name'] ?? '-'), 255),
            'username' => Str::limit((string) ($payload['username'] ?? '-'), 255),
            'ip_address' => Str::limit((string) ($payload['ip_address'] ?? $request->ip() ?? '-'), 45),
            'longitude' => isset($payload['longitude']) && $payload['longitude'] !== '' ? (string) $payload['longitude'] : null,
            'latitude' => isset($payload['latitude']) && $payload['latitude'] !== '' ? (string) $payload['latitude'] : null,
            'action' => Str::limit($action, 120),
            'detail' => $detail,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    private function permissionMenuOptions(): array
    {
        return [
            'data-blok' => 'Data Blok',
            'data-plot' => 'Data Plot',
            'data-almarhum' => 'Data Almarhum',
            'data-kontak-keluarga' => 'Data Kontak Keluarga',
            'data-user' => 'Data User',
            'activity-log' => 'Activity Log',
            'restore-data' => 'Restore Data',
            'hak-akses' => 'Hak Akses',
            'settings' => 'Pengaturan',
        ];
    }

    private function canAccessMenu(Request $request, string $menuKey): bool
    {
        if (in_array($menuKey, ['dashboard', 'account', 'logout'], true)) {
            return true;
        }

        if (! Schema::hasTable('level_sidebar_access')) {
            return true;
        }

        $authUser = $request->session()->get('auth_user', []);
        $levelId = (int) ($authUser['levelid'] ?? 0);
        if ($levelId <= 0) {
            return false;
        }

        return DB::table('level_sidebar_access')
            ->where('levelid', $levelId)
            ->where('menu_key', $menuKey)
            ->exists();
    }

    private function applyActivityLogVisibilityFilter(Request $request, \Illuminate\Database\Query\Builder $query): void
    {
        if ($this->resolveAuthRoleSlug($request) === 'superadmin') {
            return;
        }

        if (! Schema::hasTable('user') || ! Schema::hasColumn('user', 'userid') || ! Schema::hasColumn('user', 'levelid')) {
            return;
        }

        $superadminLevelIds = $this->resolveLevelIdsByRoleSlug('superadmin');
        if ($superadminLevelIds === []) {
            $superadminLevelIds = [1];
        }

        $query
            ->leftJoin('user as activity_actor', 'activity_actor.userid', '=', 'activity_logs.user_id')
            ->where(function ($filter) use ($superadminLevelIds) {
                $filter->whereNull('activity_actor.levelid')
                    ->orWhereNotIn('activity_actor.levelid', $superadminLevelIds);
            });
    }

    private function resolveAuthRoleSlug(Request $request): ?string
    {
        $authUser = $request->session()->get('auth_user', []);
        $levelId = (int) ($authUser['levelid'] ?? 0);
        if ($levelId <= 0) {
            return null;
        }

        $levelName = $this->resolveLevelName($levelId);
        if ($levelName !== null && $levelName !== '') {
            return $this->normalizeRoleSlug($levelName);
        }

        return match ($levelId) {
            1 => 'superadmin',
            2 => 'admin',
            default => null,
        };
    }

    private function resolveLevelIdsByRoleSlug(string $roleSlug): array
    {
        if (! Schema::hasTable('level') || ! Schema::hasColumn('level', 'levelid')) {
            return $roleSlug === 'superadmin' ? [1] : [];
        }

        if (! Schema::hasColumn('level', 'levelname')) {
            return $roleSlug === 'superadmin' ? [1] : [];
        }

        return DB::table('level')
            ->select(['levelid', 'levelname'])
            ->get()
            ->filter(fn ($level) => $this->normalizeRoleSlug((string) ($level->levelname ?? '')) === $roleSlug)
            ->pluck('levelid')
            ->map(fn ($levelId) => (int) $levelId)
            ->filter(fn (int $levelId) => $levelId > 0)
            ->values()
            ->all();
    }

    private function resolveLevelName(int $levelId): ?string
    {
        if ($levelId <= 0 || ! Schema::hasTable('level') || ! Schema::hasColumn('level', 'levelid')) {
            return null;
        }

        if (! Schema::hasColumn('level', 'levelname')) {
            return null;
        }

        $row = DB::table('level')
            ->select('levelname')
            ->where('levelid', $levelId)
            ->first();

        return $row?->levelname !== null ? (string) $row->levelname : null;
    }

    private function normalizeRoleSlug(?string $value): string
    {
        return (string) Str::of((string) $value)
            ->lower()
            ->replaceMatches('/[^a-z0-9]+/', '');
    }

    private function resolveAuthActor(Request $request): array
    {
        $authUser = $request->session()->get('auth_user', []);
        $id = (int) ($authUser['id'] ?? 0);
        $username = (string) ($authUser['username'] ?? '-');
        $name = (string) ($authUser['name'] ?? '');
        $latitude = $authUser['latitude'] ?? null;
        $longitude = $authUser['longitude'] ?? null;

        if ($name === '' && $id > 0) {
            $name = $this->resolveLegacyUserName($id) ?: $username;
        }

        if ($name === '') {
            $name = $username;
        }

        return [
            'id' => $id > 0 ? $id : null,
            'username' => $username ?: '-',
            'name' => $name ?: '-',
            'latitude' => $latitude,
            'longitude' => $longitude,
            'ip_address' => $request->ip(),
        ];
    }

    private function resolveLegacyUserName(int $userId): ?string
    {
        $user = LegacyUser::query()
            ->where('userid', $userId)
            ->first();

        if (! $user) {
            return null;
        }

        if (! empty($user->full_name)) {
            return (string) $user->full_name;
        }

        if (Schema::hasTable('employer') && Schema::hasColumn('employer', 'userid')) {
            $employer = DB::table('employer')
                ->where('userid', $userId)
                ->first();

            if ($employer) {
                foreach (['full_name', 'name', 'fullname', 'employee_name'] as $column) {
                    if (isset($employer->{$column}) && trim((string) $employer->{$column}) !== '') {
                        return (string) $employer->{$column};
                    }
                }
            }
        }

        return $user->username ?: null;
    }

    private function logValue(mixed $value): string
    {
        if ($value === null || $value === '') {
            return '(kosong)';
        }

        if (is_bool($value)) {
            return $value ? 'true' : 'false';
        }

        return (string) $value;
    }

    private function appendChangeDetail(array &$changes, string $field, mixed $oldValue, mixed $newValue): void
    {
        $old = $this->logValue($oldValue);
        $new = $this->logValue($newValue);

        if ($old === $new) {
            return;
        }

        $changes[] = $field . ' dari "' . $old . '" ke "' . $new . '"';
    }

    private function summarizeLogItems(array $items, int $limit = 8, string $label = 'item'): string
    {
        if (empty($items)) {
            return '-';
        }

        $safeLimit = max(1, $limit);
        $preview = array_slice($items, 0, $safeLimit);
        $text = implode('; ', $preview);
        $remaining = count($items) - count($preview);
        if ($remaining > 0) {
            $text .= '; +' . $remaining . ' ' . $label . ' lain';
        }

        return $text;
    }

    private function archiveDeletedData(Request $request, string $entityType, mixed $entityId, string $entityLabel, array $payload): void
    {
        if (! $this->hasUsableTable('restore_data')) {
            return;
        }

        $actor = $this->resolveAuthActor($request);

        try {
            DB::table('restore_data')->insert([
                'entity_type' => Str::limit($entityType, 40),
                'entity_id' => $entityId !== null ? Str::limit((string) $entityId, 60) : null,
                'entity_label' => Str::limit($entityLabel, 190),
                'payload' => json_encode($payload, JSON_UNESCAPED_UNICODE),
                'deleted_at' => now(),
                'deleted_by_user_id' => $actor['id'] ?? null,
                'deleted_by_name' => Str::limit((string) ($actor['name'] ?? '-'), 255),
                'deleted_by_username' => Str::limit((string) ($actor['username'] ?? '-'), 255),
                'ip_address' => Str::limit((string) ($actor['ip_address'] ?? '-'), 45),
                'longitude' => isset($actor['longitude']) && $actor['longitude'] !== '' ? (string) $actor['longitude'] : null,
                'latitude' => isset($actor['latitude']) && $actor['latitude'] !== '' ? (string) $actor['latitude'] : null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } catch (\Throwable $e) {
            if (! $this->isUnavailableTableException($e, 'restore_data')) {
                throw $e;
            }

            report($e);
        }
    }

    private function restoreEntityTypeLabel(string $entityType): string
    {
        return match ($entityType) {
            'block' => 'Blok',
            'plot' => 'Plot',
            'deceased' => 'Almarhum',
            'family' => 'Kontak Keluarga',
            default => 'Data',
        };
    }

    private function performRestoreEntity(string $entityType, array $payload): array
    {
        return match ($entityType) {
            'block' => $this->restoreBlockEntity($payload),
            'plot' => $this->restorePlotEntity($payload),
            'deceased' => $this->restoreDeceasedEntity($payload),
            'family' => $this->restoreFamilyEntity($payload),
            default => [false, 'Tipe data restore tidak dikenali.'],
        };
    }

    private function restoreBlockEntity(array $payload): array
    {
        if (! Schema::hasTable('blocks')) {
            return [false, 'Tabel blok tidak tersedia.'];
        }
        if (! isset($payload['blockid'])) {
            return [false, 'Payload blok tidak valid.'];
        }

        return $this->restoreTableRow('blocks', 'blockid', $payload);
    }

    private function restorePlotEntity(array $payload): array
    {
        if (! Schema::hasTable('grave_plots')) {
            return [false, 'Tabel plot tidak tersedia.'];
        }
        if (! isset($payload['plotid'])) {
            return [false, 'Payload plot tidak valid.'];
        }
        $blockId = (int) ($payload['block_id'] ?? 0);
        if ($blockId <= 0 || ! Block::query()->where('blockid', $blockId)->exists()) {
            return [false, 'Blok tujuan untuk plot belum tersedia. Restore blok terlebih dahulu.'];
        }

        return $this->restoreTableRow('grave_plots', 'plotid', $payload);
    }

    private function restoreDeceasedEntity(array $payload): array
    {
        if (! Schema::hasTable('deceased')) {
            return [false, 'Tabel almarhum tidak tersedia.'];
        }
        if (! isset($payload['deceasedid'])) {
            return [false, 'Payload almarhum tidak valid.'];
        }
        $plotId = (int) ($payload['plotid'] ?? 0);
        if ($plotId <= 0 || ! GravePlot::query()->where('plotid', $plotId)->exists()) {
            return [false, 'Plot tujuan untuk almarhum belum tersedia. Restore plot terlebih dahulu.'];
        }

        [$ok, $message] = $this->restoreTableRow('deceased', 'deceasedid', $payload);
        if (! $ok) {
            return [$ok, $message];
        }

        GravePlot::query()
            ->where('plotid', $plotId)
            ->update(['status' => 'occupied']);

        return [true, 'Data almarhum berhasil dikembalikan.'];
    }

    private function restoreFamilyEntity(array $payload): array
    {
        if (! Schema::hasTable('families')) {
            return [false, 'Tabel kontak keluarga tidak tersedia.'];
        }
        if (! isset($payload['familyid'])) {
            return [false, 'Payload kontak keluarga tidak valid.'];
        }
        $deceasedId = (int) ($payload['deceased_id'] ?? 0);
        if ($deceasedId <= 0 || ! Deceased::query()->where('deceasedid', $deceasedId)->exists()) {
            return [false, 'Almarhum tujuan untuk kontak keluarga belum tersedia. Restore data almarhum terlebih dahulu.'];
        }

        return $this->restoreTableRow('families', 'familyid', $payload);
    }

    private function restoreTableRow(string $table, string $primaryKey, array $payload): array
    {
        if (! Schema::hasTable($table)) {
            return [false, 'Tabel tujuan tidak tersedia.'];
        }
        if (! isset($payload[$primaryKey])) {
            return [false, 'Payload tidak memiliki primary key.'];
        }

        $primaryValue = $payload[$primaryKey];
        $exists = DB::table($table)
            ->where($primaryKey, $primaryValue)
            ->exists();
        if ($exists) {
            return [false, 'Data dengan ID yang sama sudah ada di tabel tujuan.'];
        }

        $allowedColumns = Schema::getColumnListing($table);
        $insertPayload = [];
        foreach ($allowedColumns as $column) {
            if (array_key_exists($column, $payload)) {
                $insertPayload[$column] = $payload[$column];
            }
        }

        if (! array_key_exists($primaryKey, $insertPayload)) {
            $insertPayload[$primaryKey] = $primaryValue;
        }
        if (in_array('created_at', $allowedColumns, true) && ! array_key_exists('created_at', $insertPayload)) {
            $insertPayload['created_at'] = now();
        }
        if (in_array('updated_at', $allowedColumns, true) && ! array_key_exists('updated_at', $insertPayload)) {
            $insertPayload['updated_at'] = now();
        }

        DB::table($table)->insert($insertPayload);

        return [true, 'Data berhasil dikembalikan.'];
    }

    private function resolveSystemSetting(): SystemSetting
    {
        $setting = SystemSetting::query()->first();

        if ($setting) {
            return $setting;
        }

        return new SystemSetting([
            'systemname' => 'Website Kuburan',
            'systemlogo' => null,
            'systemcontact' => null,
            'systemmanager' => null,
            'systemaddress' => null,
        ]);
    }

    private function buildFacilityData(): array
    {
        if (! Schema::hasTable('facility')) {
            return [
                'facilities' => collect(),
                'facilityMapItems' => collect(),
                'sceneMapItems' => collect(),
                'facilityMapStorage' => 'none',
                'facilityMapShapePersisted' => false,
            ];
        }

        $facilities = Facility::query()
            ->selectRaw('facilityid')
            ->selectRaw('COALESCE(NULLIF(facility_name, \'\'), NULLIF(name, \'\'), \'Facility\') as facility_name')
            ->selectRaw('COALESCE(NULLIF(facility_key, \'\'), CONCAT(\'facility_\', facilityid)) as facility_key')
            ->selectRaw('COALESCE(NULLIF(icon_emoji, \'\'), \'F\') as icon_emoji')
            ->orderBy('facilityid')
            ->get();

        $facilityMapStorage = $this->resolveFacilityMapStorage();

        if ($facilityMapStorage === 'facility_map_items') {
            return [
                'facilities' => $facilities,
                'facilityMapItems' => $this->loadFixedFacilityMapItems(),
                'sceneMapItems' => $this->loadFixedSceneMapItems(),
                'facilityMapStorage' => $facilityMapStorage,
                'facilityMapShapePersisted' => $this->facilityMapShapePersisted($facilityMapStorage),
            ];
        }

        if ($facilityMapStorage !== 'place') {
            return [
                'facilities' => $facilities,
                'facilityMapItems' => collect(),
                'sceneMapItems' => collect(),
                'facilityMapStorage' => $facilityMapStorage,
                'facilityMapShapePersisted' => false,
            ];
        }

        try {
            $facilityMapItemsQuery = DB::table('place')
                ->selectRaw('placeid as facility_map_itemid')
                ->selectRaw('facilityid as facility_id')
                ->selectRaw('x as map_x')
                ->selectRaw('y as map_y')
                ->when(Schema::hasColumn('place', 'map_width'), fn ($query) => $query->selectRaw('map_width as map_width'))
                ->when(Schema::hasColumn('place', 'map_height'), fn ($query) => $query->selectRaw('map_height as map_height'))
                ->when(Schema::hasColumn('place', 'map_rotation'), fn ($query) => $query->selectRaw('map_rotation as map_rotation'))
                ->orderBy('placeid');

            if (Schema::hasColumn('place', 'item_type')) {
                $facilityMapItemsQuery->where(function ($query): void {
                    $query->whereNull('item_type')
                        ->orWhere('item_type', '')
                        ->orWhere('item_type', 'icon');
                });
            }

            $facilityMapItems = $facilityMapItemsQuery
                ->get()
                ->map(function ($item) {
                    $item->is_fixed = 1;
                    if (! isset($item->map_width)) {
                        $item->map_width = null;
                    }
                    if (! isset($item->map_height)) {
                        $item->map_height = null;
                    }
                    if (! isset($item->map_rotation)) {
                        $item->map_rotation = null;
                    }
                    return $item;
                });

            $sceneMapItemsQuery = DB::table('place')
                ->selectRaw('placeid as facility_map_itemid')
                ->selectRaw('facilityid as facility_id')
                ->selectRaw('scene_object_key as scene_object_key')
                ->selectRaw('x as map_x')
                ->selectRaw('y as map_y')
                ->when(Schema::hasColumn('place', 'is_removed'), fn ($query) => $query->selectRaw('is_removed as is_removed'))
                ->when(Schema::hasColumn('place', 'map_width'), fn ($query) => $query->selectRaw('map_width as map_width'))
                ->when(Schema::hasColumn('place', 'map_height'), fn ($query) => $query->selectRaw('map_height as map_height'))
                ->when(Schema::hasColumn('place', 'map_rotation'), fn ($query) => $query->selectRaw('map_rotation as map_rotation'))
                ->orderBy('placeid');

            if (Schema::hasColumn('place', 'item_type')) {
                $sceneMapItemsQuery->where('item_type', 'scene');
            } else {
                $sceneMapItemsQuery->whereRaw('1 = 0');
            }

            $sceneMapItems = $sceneMapItemsQuery
                ->get()
                ->map(function ($item) {
                    if (! isset($item->is_removed)) {
                        $item->is_removed = false;
                    }
                    if (! isset($item->map_width)) {
                        $item->map_width = null;
                    }
                    if (! isset($item->map_height)) {
                        $item->map_height = null;
                    }
                    if (! isset($item->map_rotation)) {
                        $item->map_rotation = null;
                    }
                    return $item;
                });
        } catch (\Throwable $e) {
            if (! $this->isUnavailableTableException($e, 'place')) {
                throw $e;
            }

            report($e);

            return [
                'facilities' => $facilities,
                'facilityMapItems' => $this->loadFixedFacilityMapItems(),
                'sceneMapItems' => $this->loadFixedSceneMapItems(),
                'facilityMapStorage' => 'facility_map_items',
                'facilityMapShapePersisted' => $this->facilityMapShapePersisted('facility_map_items'),
            ];
        }

        return [
            'facilities' => $facilities,
            'facilityMapItems' => $facilityMapItems,
            'sceneMapItems' => $sceneMapItems,
            'facilityMapStorage' => $facilityMapStorage,
            'facilityMapShapePersisted' => $this->facilityMapShapePersisted($facilityMapStorage),
        ];
    }

    private function resolveFacilityMapStorage(): string
    {
        static $storage = null;

        if ($storage !== null) {
            return $storage;
        }

        if ($this->hasUsableLegacyPlaceTable()) {
            return $storage = 'place';
        }

        if ($this->hasUsableTable('facility_map_items')) {
            return $storage = 'facility_map_items';
        }

        return $storage = 'none';
    }

    private function hasUsableLegacyPlaceTable(): bool
    {
        return $this->hasUsableTable('place');
    }

    private function facilityMapItemGeometryColumns(): array
    {
        static $geometryColumns = null;

        if ($geometryColumns !== null) {
            return $geometryColumns;
        }

        if (! $this->hasUsableTable('facility_map_items')) {
            return $geometryColumns = [
                'width' => false,
                'height' => false,
                'rotation' => false,
            ];
        }

        return $geometryColumns = [
            'width' => Schema::hasColumn('facility_map_items', 'map_width'),
            'height' => Schema::hasColumn('facility_map_items', 'map_height'),
            'rotation' => Schema::hasColumn('facility_map_items', 'map_rotation'),
        ];
    }

    private function facilityMapItemSceneColumns(): array
    {
        static $sceneColumns = null;

        if ($sceneColumns !== null) {
            return $sceneColumns;
        }

        if (! $this->hasUsableTable('facility_map_items')) {
            return $sceneColumns = [
                'item_type' => false,
                'scene_object_key' => false,
                'is_removed' => false,
            ];
        }

        return $sceneColumns = [
            'item_type' => Schema::hasColumn('facility_map_items', 'item_type'),
            'scene_object_key' => Schema::hasColumn('facility_map_items', 'scene_object_key'),
            'is_removed' => Schema::hasColumn('facility_map_items', 'is_removed'),
        ];
    }

    private function facilityMapShapePersisted(string $storage): bool
    {
        if ($storage === 'place') {
            return true;
        }

        if ($storage !== 'facility_map_items') {
            return false;
        }

        $geometryColumns = $this->facilityMapItemGeometryColumns();

        return $geometryColumns['width']
            && $geometryColumns['height']
            && $geometryColumns['rotation'];
    }

    private function makePersistedFacilityItemState(array $item, int $savedId): array
    {
        return [
            'facility_map_itemid' => $savedId,
            'facility_id' => (int) ($item['facility_id'] ?? 0),
            'map_x' => (int) ($item['x'] ?? 0),
            'map_y' => (int) ($item['y'] ?? 0),
            'map_width' => $item['width'] !== null ? (int) $item['width'] : null,
            'map_height' => $item['height'] !== null ? (int) $item['height'] : null,
            'map_rotation' => $item['rotation'] !== null ? (float) $item['rotation'] : null,
            'is_fixed' => (bool) ($item['is_fixed'] ?? true),
        ];
    }

    private function makePersistedSceneItemState(array $item, int $savedId): array
    {
        $sceneObjectKey = trim((string) ($item['scene_object_key'] ?? ''));

        return [
            'facility_map_itemid' => $savedId,
            'scene_object_key' => $sceneObjectKey !== '' ? $sceneObjectKey : null,
            'facility_id' => max(0, (int) ($item['facility_id'] ?? 0)),
            'map_x' => (int) ($item['x'] ?? 0),
            'map_y' => (int) ($item['y'] ?? 0),
            'map_width' => $item['width'] !== null ? (int) $item['width'] : null,
            'map_height' => $item['height'] !== null ? (int) $item['height'] : null,
            'map_rotation' => $item['rotation'] !== null ? (float) $item['rotation'] : null,
            'is_removed' => (bool) ($item['is_removed'] ?? false),
        ];
    }

    private function hasUsableTable(string $table): bool
    {
        static $usableTables = [];

        if (array_key_exists($table, $usableTables)) {
            return $usableTables[$table];
        }

        if (! Schema::hasTable($table)) {
            return $usableTables[$table] = false;
        }

        try {
            DB::table($table)
                ->selectRaw('1')
                ->limit(1)
                ->get();

            return $usableTables[$table] = true;
        } catch (\Throwable $e) {
            if (! $this->isUnavailableTableException($e, $table)) {
                throw $e;
            }

            report($e);

            return $usableTables[$table] = false;
        }
    }

    private function loadFixedFacilityMapItems()
    {
        if (! $this->hasUsableTable('facility_map_items')) {
            return collect();
        }

        $geometryColumns = $this->facilityMapItemGeometryColumns();
        $sceneColumns = $this->facilityMapItemSceneColumns();

        try {
            $query = DB::table('facility_map_items')
                ->selectRaw('facility_map_itemid as facility_map_itemid')
                ->selectRaw('facility_id as facility_id')
                ->selectRaw('map_x as map_x')
                ->selectRaw('map_y as map_y')
                ->when($geometryColumns['width'], fn ($query) => $query->selectRaw('map_width as map_width'))
                ->when($geometryColumns['height'], fn ($query) => $query->selectRaw('map_height as map_height'))
                ->when($geometryColumns['rotation'], fn ($query) => $query->selectRaw('map_rotation as map_rotation'))
                ->where('is_fixed', true)
                ->orderBy('facility_map_itemid');

            if ($sceneColumns['item_type']) {
                $query->where(function ($builder): void {
                    $builder->whereNull('item_type')
                        ->orWhere('item_type', '')
                        ->orWhere('item_type', 'icon');
                });
            }

            return $query
                ->get()
                ->map(function ($item) {
                    $item->is_fixed = 1;
                    if (! isset($item->map_width)) {
                        $item->map_width = null;
                    }
                    if (! isset($item->map_height)) {
                        $item->map_height = null;
                    }
                    if (! isset($item->map_rotation)) {
                        $item->map_rotation = null;
                    }
                    return $item;
                });
        } catch (\Throwable $e) {
            if (! $this->isUnavailableTableException($e, 'facility_map_items')) {
                throw $e;
            }

            report($e);

            return collect();
        }
    }

    private function loadFixedSceneMapItems()
    {
        if (! $this->hasUsableTable('facility_map_items')) {
            return collect();
        }

        $geometryColumns = $this->facilityMapItemGeometryColumns();
        $sceneColumns = $this->facilityMapItemSceneColumns();

        if (! $sceneColumns['item_type'] || ! $sceneColumns['scene_object_key']) {
            return collect();
        }

        try {
            return DB::table('facility_map_items')
                ->selectRaw('facility_map_itemid as facility_map_itemid')
                ->selectRaw('facility_id as facility_id')
                ->selectRaw('scene_object_key as scene_object_key')
                ->selectRaw('map_x as map_x')
                ->selectRaw('map_y as map_y')
                ->when($sceneColumns['is_removed'], fn ($query) => $query->selectRaw('is_removed as is_removed'))
                ->when($geometryColumns['width'], fn ($query) => $query->selectRaw('map_width as map_width'))
                ->when($geometryColumns['height'], fn ($query) => $query->selectRaw('map_height as map_height'))
                ->when($geometryColumns['rotation'], fn ($query) => $query->selectRaw('map_rotation as map_rotation'))
                ->where('is_fixed', true)
                ->where('item_type', 'scene')
                ->whereNotNull('scene_object_key')
                ->where('scene_object_key', '!=', '')
                ->orderBy('facility_map_itemid')
                ->get()
                ->map(function ($item) {
                    if (! isset($item->is_removed)) {
                        $item->is_removed = false;
                    }
                    if (! isset($item->map_width)) {
                        $item->map_width = null;
                    }
                    if (! isset($item->map_height)) {
                        $item->map_height = null;
                    }
                    if (! isset($item->map_rotation)) {
                        $item->map_rotation = null;
                    }
                    return $item;
                });
        } catch (\Throwable $e) {
            if (! $this->isUnavailableTableException($e, 'facility_map_items')) {
                throw $e;
            }

            report($e);

            return collect();
        }
    }

    private function isUnavailableTableException(\Throwable $e, string $table): bool
    {
        $message = Str::lower($e->getMessage());
        $table = Str::lower($table);

        $tableMentioned = Str::contains($message, [
            "'{$table}'",
            "`{$table}`",
            ".{$table}'",
            ".{$table}`",
        ]);

        if (! $tableMentioned) {
            return false;
        }

        return Str::contains($message, [
            'base table or view not found',
            'doesn\'t exist in engine',
            'doesn\'t exist',
            'no such table',
        ]);
    }

    private function buildDashboardData(bool $hideBlocksWithoutPlots = false): array
    {
        $summary = GravePlot::query()
            ->selectRaw('COUNT(*) as total')
            ->selectRaw("SUM(CASE WHEN status = 'occupied' THEN 1 ELSE 0 END) as occupied")
            ->selectRaw("SUM(CASE WHEN status = 'empty' THEN 1 ELSE 0 END) as empty")
            ->first();

        $dashboardBlockColumns = ['blockid', 'block_name', 'map_color'];
        if (Schema::hasColumn('blocks', 'map_x')) {
            $dashboardBlockColumns[] = 'map_x';
        }
        if (Schema::hasColumn('blocks', 'map_y')) {
            $dashboardBlockColumns[] = 'map_y';
        }

        $blocks = Block::query()
            ->select($dashboardBlockColumns)
            ->withCount([
                'gravePlots as total_plots',
                'gravePlots as occupied_plots' => fn ($query) => $query->where('status', 'occupied'),
                'gravePlots as empty_plots' => fn ($query) => $query->where('status', 'empty'),
            ])
            ->with([
                'gravePlots' => fn ($query) => $query
                    ->select([
                        'plotid',
                        'block_id',
                        'plot_number',
                        'row_number',
                        'position_x',
                        'position_y',
                        'width',
                        'height',
                        'status',
                    ])
                    ->orderBy('row_number')
                    ->orderBy('plot_number'),
            ])
            ->orderBy('blockid')
            ->get();

        if ($hideBlocksWithoutPlots) {
            $blocks = $blocks->filter(fn ($block) => $block->gravePlots->isNotEmpty())->values();
        }

        $plotIds = $blocks
            ->flatMap(fn ($block) => $block->gravePlots->pluck('plotid'))
            ->unique()
            ->values();

        $deceasedByPlot = Deceased::query()
            ->whereIn('plotid', $plotIds)
            ->orderByDesc('death_date')
            ->orderByDesc('deceasedid')
            ->get()
            ->unique('plotid')
            ->keyBy('plotid');

        $blockMaps = [];
        $mapPlots = [];
        $cursorX = 24;
        $mapHeight = 360;

        foreach ($blocks as $block) {
            $plots = $block->gravePlots;
            $color = $this->normalizeMapColor($block->map_color, '#D8E4DF');

            $minX = 0.0;
            $minY = 0.0;
            $maxX = 220.0;
            $maxY = 140.0;

            if ($plots->isNotEmpty()) {
                $minX = (float) $plots->min('position_x');
                $minY = (float) $plots->min('position_y');
                $maxX = 0.0;
                $maxY = 0.0;

                foreach ($plots as $plot) {
                    $plotWidth = (float) ($plot->width ?? 60);
                    $plotHeight = (float) ($plot->height ?? 40);
                    $plotEndX = (float) $plot->position_x + $plotWidth;
                    $plotEndY = (float) $plot->position_y + $plotHeight;

                    if ($plotEndX > $maxX) {
                        $maxX = $plotEndX;
                    }

                    if ($plotEndY > $maxY) {
                        $maxY = $plotEndY;
                    }
                }
            }

            $blockWidth = max(240, (int) ceil(($maxX - $minX) + 70));
            $blockHeight = max(180, (int) ceil(($maxY - $minY) + 78));
            $blockX = isset($block->map_x) ? (int) $block->map_x : $cursorX;
            $blockY = isset($block->map_y) ? (int) $block->map_y : 40;

            $blockMaps[] = [
                'id' => (int) $block->blockid,
                'name' => $block->block_name,
                'x' => $blockX,
                'y' => $blockY,
                'width' => $blockWidth,
                'height' => $blockHeight,
                'color' => $color,
                'total_plots' => (int) $block->total_plots,
            ];

            foreach ($plots as $plot) {
                $plotWidth = (float) ($plot->width ?? 60);
                $plotHeight = (float) ($plot->height ?? 40);
                $normalizedStatus = ($plot->status ?? 'empty') === 'occupied' ? 'occupied' : 'empty';
                $deceased = $deceasedByPlot->get($plot->plotid);
                $deathDate = $deceased?->death_date ? Carbon::parse($deceased->death_date) : null;
                $birthDate = $deceased?->birth_date ? Carbon::parse($deceased->birth_date) : null;
                $ageAtDeath = ($deathDate && $birthDate)
                    ? (int) floor($birthDate->diffInYears($deathDate, true))
                    : null;
                $photoUrl = $this->resolvePhotoUrl($deceased?->photo_url);

                $mapPlots[] = [
                    'plot_id' => (int) $plot->plotid,
                    'block_id' => (int) $block->blockid,
                    'plot_number' => $plot->plot_number,
                    'row_number' => $plot->row_number,
                    'plot_label' => $plot->row_number
                        ? 'Baris ' . $plot->row_number . ' - Plot ' . $plot->plot_number
                        : 'Plot ' . $plot->plot_number,
                    'status' => $normalizedStatus,
                    'x' => $blockX + ((float) $plot->position_x - $minX) + 28,
                    'y' => $blockY + ((float) $plot->position_y - $minY) + 38,
                    'width' => $plotWidth,
                    'height' => $plotHeight,
                    'deceased_id' => $deceased?->deceasedid,
                    'deceased_name' => $deceased?->full_name,
                    'deceased_age' => $ageAtDeath,
                    'deceased_death_date' => $deathDate?->format('d M Y'),
                    'deceased_photo_url' => $photoUrl,
                ];
            }

            $cursorX = max($cursorX, $blockX + $blockWidth + 24);
            $mapHeight = max($mapHeight, $blockY + $blockHeight + 32);
        }

        $mapWidth = max(900, $cursorX + 16);

        return [
            'summary' => $summary,
            'blocks' => $blocks,
            'blockMaps' => $blockMaps,
            'mapPlots' => $mapPlots,
            'mapWidth' => $mapWidth,
            'mapHeight' => $mapHeight,
        ];
    }

    private function resolveChatbotAnswer(string $message): string
    {
        $normalized = Str::lower(trim($message));
        if ($normalized === '') {
            return 'Silakan tulis pertanyaan, misalnya: "Kuburan Bapak Ahmad di mana?".';
        }

        $greetingWords = ['halo', 'hai', 'selamat', 'pagi', 'siang', 'sore', 'malam'];
        foreach ($greetingWords as $word) {
            if (str_contains($normalized, $word)) {
                return 'Halo. Saya bisa bantu cari lokasi kuburan berdasarkan nama, info blok, atau plot kosong.';
            }
        }

        $isLocationIntent = str_contains($normalized, 'dimana')
            || str_contains($normalized, 'di mana')
            || str_contains($normalized, 'lokasi')
            || str_contains($normalized, 'letak')
            || str_contains($normalized, 'berada');

        $nameCandidate = $this->extractPotentialDeceasedName($message);
        if ($nameCandidate !== null) {
            $deceasedMatches = $this->findDeceasedByName($nameCandidate);
            if ($deceasedMatches->isNotEmpty()) {
                $first = $deceasedMatches->first();
                $plotLabel = ! empty($first->row_number)
                    ? 'Baris ' . $first->row_number . ', Plot ' . $first->plot_number
                    : 'Plot ' . ($first->plot_number ?? '-');
                $blockLabel = $first->block_name ?: 'blok belum ditentukan';
                $response = $first->full_name . ' berada di ' . $blockLabel . ' - ' . $plotLabel . '.';

                if (! empty($first->death_date)) {
                    $response .= ' Tanggal wafat: ' . Carbon::parse($first->death_date)->format('d M Y') . '.';
                }
                if (! empty($first->burial_date)) {
                    $response .= ' Tanggal pemakaman: ' . Carbon::parse($first->burial_date)->format('d M Y') . '.';
                }

                if ($deceasedMatches->count() > 1) {
                    $otherNames = $deceasedMatches
                        ->skip(1)
                        ->take(3)
                        ->pluck('full_name')
                        ->filter()
                        ->unique()
                        ->values()
                        ->all();

                    if (! empty($otherNames)) {
                        $response .= ' Ada nama mirip lain: ' . implode(', ', $otherNames) . '.';
                    }
                }

                return $response;
            }

            if ($isLocationIntent || str_contains($normalized, 'kubur') || str_contains($normalized, 'makam')) {
                return 'Data kuburan atas nama "' . $nameCandidate . '" belum ditemukan. Coba kirim nama lengkap yang lebih spesifik.';
            }
        }

        if (preg_match('/blok\s+([a-z0-9]+)/iu', $message, $blockMatch) === 1) {
            $blockCode = strtoupper((string) $blockMatch[1]);
            $blockKeyword = 'blok ' . Str::lower($blockCode);

            $block = Block::query()
                ->select(['blockid', 'block_name', 'map_x', 'map_y'])
                ->withCount([
                    'gravePlots as total_plots',
                    'gravePlots as occupied_plots' => fn ($query) => $query->where('status', 'occupied'),
                    'gravePlots as empty_plots' => fn ($query) => $query->where('status', 'empty'),
                ])
                ->whereRaw('LOWER(block_name) = ?', [$blockKeyword])
                ->orWhereRaw('LOWER(block_name) LIKE ?', ['%' . $blockKeyword . '%'])
                ->orderBy('blockid')
                ->first();

            if ($block) {
                $positionText = (isset($block->map_x) || isset($block->map_y))
                    ? ' Posisi denah: X ' . $this->logValue($block->map_x) . ', Y ' . $this->logValue($block->map_y) . '.'
                    : '';

                if (str_contains($normalized, 'kosong') || str_contains($normalized, 'tersedia')) {
                    $emptyPlots = GravePlot::query()
                        ->select(['plot_number', 'row_number'])
                        ->where('block_id', $block->blockid)
                        ->where('status', 'empty')
                        ->orderBy('row_number')
                        ->orderBy('plot_number')
                        ->limit(5)
                        ->get();

                    if ($emptyPlots->isEmpty()) {
                        return $block->block_name . ' saat ini tidak memiliki plot kosong.' . $positionText;
                    }

                    $emptyLabels = $emptyPlots->map(function ($plot): string {
                        if (! empty($plot->row_number)) {
                            return 'Baris ' . $plot->row_number . ' Plot ' . $plot->plot_number;
                        }

                        return 'Plot ' . $plot->plot_number;
                    })->all();

                    return $block->block_name . ' memiliki ' . (int) $block->empty_plots . ' plot kosong. Contoh: ' . implode(', ', $emptyLabels) . '.' . $positionText;
                }

                return $block->block_name
                    . ': total plot ' . (int) $block->total_plots
                    . ', terisi ' . (int) $block->occupied_plots
                    . ', kosong ' . (int) $block->empty_plots
                    . '.' . $positionText;
            }
        }

        if (str_contains($normalized, 'plot kosong') || str_contains($normalized, 'plot tersedia') || str_contains($normalized, 'petak kosong')) {
            $emptyPlots = GravePlot::query()
                ->select(['grave_plots.plot_number', 'grave_plots.row_number', 'blocks.block_name'])
                ->leftJoin('blocks', 'blocks.blockid', '=', 'grave_plots.block_id')
                ->where('grave_plots.status', 'empty')
                ->orderBy('blocks.block_name')
                ->orderBy('grave_plots.row_number')
                ->orderBy('grave_plots.plot_number')
                ->limit(8)
                ->get();

            if ($emptyPlots->isEmpty()) {
                return 'Saat ini tidak ada plot kosong yang terdata.';
            }

            $labels = $emptyPlots->map(function ($plot): string {
                $plotLabel = ! empty($plot->row_number)
                    ? 'Baris ' . $plot->row_number . ' Plot ' . $plot->plot_number
                    : 'Plot ' . $plot->plot_number;
                return ($plot->block_name ?: 'Blok -') . ' (' . $plotLabel . ')';
            })->all();

            return 'Contoh plot kosong yang tersedia: ' . implode(', ', $labels) . '.';
        }

        return 'Saya bisa bantu: cari lokasi kuburan berdasarkan nama, cek info blok (misal: "info Blok A"), atau cek plot kosong (misal: "plot kosong di Blok B").';
    }

    private function extractPotentialDeceasedName(string $message): ?string
    {
        $trimmed = trim($message);
        if ($trimmed === '') {
            return null;
        }

        if (preg_match('/["\']([^"\']{3,120})["\']/u', $trimmed, $quoted) === 1) {
            return trim($quoted[1]);
        }

        if (preg_match('/(?:kubur(?:an)?|makam|almarhum|alm\.?|atas nama|nama)\s+([a-z0-9\s\.\'-]{3,120})/iu', $trimmed, $captured) === 1) {
            $candidate = trim((string) $captured[1]);
            $candidate = preg_replace('/\b(dimana|di mana|berada|letak|lokasi)\b.*/iu', '', $candidate);
            $candidate = trim((string) $candidate);
            if ($candidate !== '' && ! str_contains(Str::lower($candidate), 'blok')) {
                return $candidate;
            }
        }

        return null;
    }

    private function findDeceasedByName(string $name)
    {
        $normalizedName = Str::lower(trim($name));

        return Deceased::query()
            ->select([
                'deceased.deceasedid',
                'deceased.full_name',
                'deceased.death_date',
                'deceased.burial_date',
                'grave_plots.plot_number',
                'grave_plots.row_number',
                'blocks.block_name',
            ])
            ->leftJoin('grave_plots', 'grave_plots.plotid', '=', 'deceased.plotid')
            ->leftJoin('blocks', 'blocks.blockid', '=', 'grave_plots.block_id')
            ->where('deceased.full_name', 'like', '%' . $name . '%')
            ->orderByRaw(
                "CASE
                    WHEN LOWER(deceased.full_name) = ? THEN 0
                    WHEN LOWER(deceased.full_name) LIKE ? THEN 1
                    ELSE 2
                END",
                [$normalizedName, $normalizedName . '%']
            )
            ->orderBy('deceased.full_name')
            ->limit(5)
            ->get();
    }

    private function validateDataBlokPayload(Request $request): array
    {
        $rules = [
            'block_name' => ['required', 'string', 'max:100'],
            'description' => ['nullable', 'string', 'max:2000'],
            'map_color' => ['required', 'regex:/^#[0-9A-Fa-f]{6}$/'],
        ];

        if (Schema::hasColumn('blocks', 'max_plots')) {
            $rules['max_plots'] = ['required', 'integer', 'min:1', 'max:500'];
        }
        if (Schema::hasColumn('blocks', 'map_x')) {
            $rules['map_x'] = ['nullable', 'integer'];
        }
        if (Schema::hasColumn('blocks', 'map_y')) {
            $rules['map_y'] = ['nullable', 'integer'];
        }

        return $request->validate($rules);
    }

    private function formatBlockRow(Block $block): array
    {
        return [
            'blockid' => (int) $block->blockid,
            'block_name' => $block->block_name,
            'description' => $block->description,
            'map_color' => $this->normalizeMapColor($block->map_color, '#D8E4DF'),
            'max_plots' => max(1, (int) ($block->max_plots ?? 15)),
            'map_x' => isset($block->map_x) ? (int) $block->map_x : null,
            'map_y' => isset($block->map_y) ? (int) $block->map_y : null,
            'total_plots' => (int) ($block->total_plots ?? 0),
            'occupied_plots' => (int) ($block->occupied_plots ?? 0),
            'empty_plots' => (int) ($block->empty_plots ?? 0),
            'reserved_plots' => 0,
            'maintenance_plots' => 0,
        ];
    }

    private function normalizeMapColor(mixed $color, string $fallback = '#D8E4DF'): string
    {
        $raw = trim((string) ($color ?? ''));
        if ($raw === '') {
            return $fallback;
        }

        if (preg_match('/^#[0-9a-fA-F]{6}$/', $raw) === 1) {
            return strtoupper($raw);
        }

        if (preg_match('/^[0-9a-fA-F]{6}$/', $raw) === 1) {
            return '#' . strtoupper($raw);
        }

        return $fallback;
    }

    public function deceasedDetail(Request $request, int $id): View
    {
        $deceased = Deceased::query()
            ->select([
                'deceased.deceasedid',
                'deceased.full_name',
                'deceased.gender',
                'deceased.birth_date',
                'deceased.death_date',
                'deceased.burial_date',
                'deceased.religion',
                'deceased.identity_number',
                'deceased.address',
                'deceased.description',
                'deceased.photo_url',
                'grave_plots.plot_number',
                'grave_plots.row_number',
                'blocks.block_name',
            ])
            ->leftJoin('grave_plots', 'grave_plots.plotid', '=', 'deceased.plotid')
            ->leftJoin('blocks', 'blocks.blockid', '=', 'grave_plots.block_id')
            ->where('deceased.deceasedid', $id)
            ->firstOrFail();

        $deathDate = $deceased->death_date ? Carbon::parse($deceased->death_date) : null;
        $birthDate = $deceased->birth_date ? Carbon::parse($deceased->birth_date) : null;
        $ageAtDeath = ($deathDate && $birthDate)
            ? (int) floor($birthDate->diffInYears($deathDate, true))
            : null;
        $photoUrl = $this->resolvePhotoUrl($deceased->photo_url);

        $familyContacts = DB::table('families')
            ->select([
                'familyid',
                'family_name',
                'relationship_status',
                'phone_number',
                'email',
                'address',
                'notes',
            ])
            ->where('deceased_id', $id)
            ->orderBy('familyid')
            ->get();

        return view('deceased-detail', [
            'deceased' => $deceased,
            'ageAtDeath' => $ageAtDeath,
            'photoUrl' => $photoUrl,
            'familyContacts' => $familyContacts,
        ]);
    }
}
