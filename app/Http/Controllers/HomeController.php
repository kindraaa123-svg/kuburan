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

        $blockColumns = ['blockid', 'block_name', 'description', 'map_color'];
        if (Schema::hasColumn('blocks', 'max_plots')) {
            $blockColumns[] = 'max_plots';
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

        return view('data-blok', [
            'authUser' => $request->session()->get('auth_user'),
            'blocks' => $blocks,
        ]);
    }

    public function dataPlot(Request $request): View|RedirectResponse
    {
        if (! $request->session()->has('auth_user')) {
            return redirect('/login')
                ->with('status', 'Silakan login terlebih dahulu.');
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

    public function storeDataAlmarhum(Request $request): RedirectResponse
    {
        if (! $request->session()->has('auth_user')) {
            return redirect('/login')
                ->with('status', 'Silakan login terlebih dahulu.');
        }

        $validated = $this->validateDeceasedPayload($request);
        $plotId = (int) $validated['plotid'];

        $existing = Deceased::query()
            ->where('plotid', $plotId)
            ->exists();

        if ($existing) {
            return back()
                ->withInput()
                ->withErrors([
                    'plotid' => 'Plot ini sudah terisi data almarhum.',
                ], 'almarhumForm');
        }

        Deceased::query()->create([
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

        return redirect()
            ->route('dashboard.data-almarhum')
            ->with('status', 'Data almarhum berhasil ditambahkan.');
    }

    public function updateDataAlmarhum(Request $request, Deceased $deceased): RedirectResponse
    {
        if (! $request->session()->has('auth_user')) {
            return redirect('/login')
                ->with('status', 'Silakan login terlebih dahulu.');
        }

        $validated = $this->validateDeceasedPayload($request);
        $plotId = (int) $validated['plotid'];

        $existing = Deceased::query()
            ->where('plotid', $plotId)
            ->where('deceasedid', '!=', (int) $deceased->deceasedid)
            ->exists();

        if ($existing) {
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

        return redirect()
            ->route('dashboard.data-almarhum')
            ->with('status', 'Data almarhum berhasil diperbarui.');
    }

    public function dataUser(Request $request): View|RedirectResponse
    {
        if (! $request->session()->has('auth_user')) {
            return redirect('/login')
                ->with('status', 'Silakan login terlebih dahulu.');
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

    public function storeUser(Request $request): RedirectResponse
    {
        if (! $request->session()->has('auth_user')) {
            return redirect('/login')
                ->with('status', 'Silakan login terlebih dahulu.');
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

        DB::transaction(function () use ($validated, $defaultPassword, $hasUserFullName, $hasUserEmail, $hasUserPhone): void {
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

        $defaultPassword = '12345';
        $user->password = Hash::make($defaultPassword);
        $user->reset_password_token = null;
        $user->reset_password_token_expired = null;
        $user->save();

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

        $authUser = $request->session()->get('auth_user');
        if ((int) ($authUser['id'] ?? 0) === (int) $user->userid) {
            return redirect()
                ->route('dashboard.data-user')
                ->with('status', 'Akun yang sedang login tidak bisa dihapus.');
        }

        $username = $user->username;
        $user->delete();

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

        $plot->fill([
            'plot_number' => $validated['plot_number'],
            'row_number' => $validated['row_number'] ?: null,
            'position_x' => (float) $validated['position_x'],
            'position_y' => (float) $validated['position_y'],
            'width' => (float) $validated['width'],
            'height' => (float) $validated['height'],
        ]);
        $plot->save();

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

        $validated = $this->validateDataBlokPayload($request);

        $createPayload = [
            'block_name' => $validated['block_name'],
            'description' => $validated['description'] ?? null,
            'map_color' => strtoupper($validated['map_color']),
        ];
        if (Schema::hasColumn('blocks', 'max_plots')) {
            $createPayload['max_plots'] = (int) ($validated['max_plots'] ?? 15);
        }

        $block = Block::query()->create($createPayload);

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

        $validated = $this->validateDataBlokPayload($request);

        $updatePayload = [
            'block_name' => $validated['block_name'],
            'description' => $validated['description'] ?? null,
            'map_color' => strtoupper($validated['map_color']),
        ];
        if (Schema::hasColumn('blocks', 'max_plots')) {
            $updatePayload['max_plots'] = (int) ($validated['max_plots'] ?? 15);
        }

        $block->fill($updatePayload);
        $block->save();

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

        $totalPlots = GravePlot::query()
            ->where('block_id', $block->blockid)
            ->count();

        if ($totalPlots > 0) {
            return response()->json([
                'message' => 'Blok tidak bisa dihapus karena masih memiliki data petak.',
            ], 422);
        }

        $block->delete();

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

        $user->full_name = $validated['full_name'];
        $user->phone_number = $validated['phone_number'] ?: null;
        $user->email = $validated['email'] ?: null;
        $user->save();

        $request->session()->put('auth_user', [
            'id' => (int) $user->userid,
            'username' => $user->username,
            'levelid' => (int) $user->levelid,
        ]);

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

        return redirect()
            ->route('dashboard.account')
            ->with('password_status', 'Password berhasil diubah.');
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

        $blocks = Block::query()
            ->select(['blockid', 'block_name', 'map_color'])
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
            $blockX = $cursorX;
            $blockY = 40;

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

            $cursorX += $blockWidth + 24;
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
