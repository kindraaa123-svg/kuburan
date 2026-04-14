<?php

namespace App\Http\Controllers;

use App\Models\Block;
use App\Models\Deceased;
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
        $data = $this->buildDashboardData(true);
        $setting = $this->resolveSystemSetting();

        return view('home', [
            ...$data,
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

        return view('dashboard', [
            ...$data,
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

        DB::table('families')
            ->where('familyid', $familyid)
            ->delete();

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
        $deceased->delete();

        if ($photoPath && ! Str::startsWith($photoPath, ['http://', 'https://', '/'])) {
            Storage::disk('public')->delete($photoPath);
        }

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

    public function activityLog(Request $request): View|RedirectResponse
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
            $logs = DB::table('activity_logs')
                ->select([
                    'name',
                    'username',
                    'ip_address',
                    'longitude',
                    'latitude',
                    'action',
                    'detail',
                    'created_at',
                ])
                ->orderByDesc('created_at')
                ->limit(300)
                ->get()
                ->map(function ($row) use ($timezone) {
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

        return view('activity-log', [
            'authUser' => $request->session()->get('auth_user'),
            'logs' => $logs,
        ]);
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

        $plot->delete();

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

        $validated = $request->validate([
            'blocks' => ['required', 'array', 'min:1'],
            'blocks.*.id' => ['required', 'integer', 'exists:blocks,blockid'],
            'blocks.*.x' => ['required', 'integer'],
            'blocks.*.y' => ['required', 'integer'],
        ]);

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

        DB::transaction(function () use ($incomingPositions, $targetBlocks): void {
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
        $detail .= empty($changes) ? 'Tidak ada perubahan nilai.' : implode('; ', $changes) . '.';
        $this->writeActivityLog($request, 'Edit Posisi Blok', $detail);

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'message' => 'Posisi denah blok berhasil disimpan.',
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

        $block->delete();

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

        return view('account-settings', [
            'authUser' => $authUser,
            'userAccount' => $user,
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

    private function buildDashboardData(bool $hideBlocksWithoutPlots = false): array
    {
        $summary = GravePlot::query()
            ->selectRaw('COUNT(*) as total')
            ->selectRaw("SUM(CASE WHEN status = 'occupied' THEN 1 ELSE 0 END) as occupied")
            ->selectRaw("SUM(CASE WHEN status = 'empty' THEN 1 ELSE 0 END) as empty")
            ->selectRaw("SUM(CASE WHEN status = 'reserved' THEN 1 ELSE 0 END) as reserved")
            ->selectRaw("SUM(CASE WHEN status = 'maintenance' THEN 1 ELSE 0 END) as maintenance")
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
            $color = $block->map_color ?: '#D8E4DF';

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
            'map_color' => $block->map_color ?: '#D8E4DF',
            'max_plots' => max(1, (int) ($block->max_plots ?? 15)),
            'map_x' => isset($block->map_x) ? (int) $block->map_x : null,
            'map_y' => isset($block->map_y) ? (int) $block->map_y : null,
            'total_plots' => (int) ($block->total_plots ?? 0),
            'occupied_plots' => (int) ($block->occupied_plots ?? 0),
            'empty_plots' => (int) ($block->empty_plots ?? 0),
            'reserved_plots' => (int) ($block->reserved_plots ?? 0),
            'maintenance_plots' => (int) ($block->maintenance_plots ?? 0),
        ];
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
