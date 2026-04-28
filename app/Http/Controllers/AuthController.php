<?php

namespace App\Http\Controllers;

use App\Models\LegacyUser;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function showLogin(Request $request): View|RedirectResponse
    {
        if ($request->session()->has('auth_user')) {
            return redirect('/dashboard');
        }

        $this->storeOfflineCaptcha($request);

        return view('auth.login');
    }

    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
            'captcha_mode' => ['nullable', 'in:online,offline'],
            'g-recaptcha-response' => ['nullable', 'string'],
            'offline_captcha_answer' => ['nullable', 'string'],
            'latitude' => ['nullable', 'numeric', 'between:-90,90'],
            'longitude' => ['nullable', 'numeric', 'between:-180,180'],
        ], [
            'g-recaptcha-response.required' => 'Silakan selesaikan verifikasi reCAPTCHA terlebih dahulu.',
            'offline_captcha_answer.required' => 'Silakan isi captcha aritmatika terlebih dahulu.',
        ]);

        $captchaMode = (string) ($credentials['captcha_mode'] ?? 'offline');

        if ($captchaMode === 'online') {
            $captchaResponse = trim((string) ($credentials['g-recaptcha-response'] ?? ''));
            if ($captchaResponse === '') {
                return $this->rejectLogin($request, 'Silakan selesaikan verifikasi reCAPTCHA terlebih dahulu.');
            }

            $captchaSecret = (string) config('services.recaptcha.secret_key');
            if ($captchaSecret === '') {
                return $this->rejectLogin($request, 'Google reCAPTCHA belum dikonfigurasi di server.');
            }

            try {
                $captchaVerification = Http::asForm()
                    ->timeout(8)
                    ->post('https://www.google.com/recaptcha/api/siteverify', [
                        'secret' => $captchaSecret,
                        'response' => $captchaResponse,
                        'remoteip' => $request->ip(),
                    ]);
            } catch (\Throwable $exception) {
                return $this->rejectLogin($request, 'Gagal memverifikasi reCAPTCHA. Coba lagi sebentar.');
            }

            if (! $captchaVerification->ok() || ! data_get($captchaVerification->json(), 'success', false)) {
                return $this->rejectLogin($request, 'Verifikasi reCAPTCHA tidak valid. Silakan coba lagi.');
            }
        } else {
            $offlineAnswer = trim((string) ($credentials['offline_captcha_answer'] ?? ''));
            if ($offlineAnswer === '') {
                return $this->rejectLogin($request, 'Silakan isi captcha aritmatika terlebih dahulu.');
            }

            $expectedAnswer = (string) $request->session()->get('offline_captcha_answer', '');
            if ($expectedAnswer === '' || ! hash_equals($expectedAnswer, $offlineAnswer)) {
                return $this->rejectLogin($request, 'Captcha aritmatika tidak valid. Silakan coba lagi.');
            }
        }

        $user = LegacyUser::query()
            ->where('username', $credentials['username'])
            ->first();

        if (! $user) {
            return $this->rejectLogin($request, 'Username atau password salah.');
        }

        $validPassword = Hash::check($credentials['password'], (string) $user->password)
            || hash_equals((string) $user->password, $credentials['password']);

        if (! $validPassword) {
            return $this->rejectLogin($request, 'Username atau password salah.');
        }

        $displayName = $this->resolveUserDisplayName($user);
        $latitude = isset($credentials['latitude']) && $credentials['latitude'] !== ''
            ? (string) $credentials['latitude']
            : null;
        $longitude = isset($credentials['longitude']) && $credentials['longitude'] !== ''
            ? (string) $credentials['longitude']
            : null;

        $request->session()->regenerate();
        $request->session()->put('auth_user', [
            'id' => (int) $user->userid,
            'username' => $user->username,
            'levelid' => (int) $user->levelid,
            'name' => $displayName,
            'latitude' => $latitude,
            'longitude' => $longitude,
        ]);

        $this->writeActivityLog([
            'user_id' => (int) $user->userid,
            'name' => $displayName,
            'username' => $user->username,
            'ip_address' => $request->ip(),
            'longitude' => $longitude,
            'latitude' => $latitude,
            'action' => 'Login',
            'detail' => 'User login ke sistem.',
        ]);

        return redirect('/dashboard')
            ->with('status', 'Login berhasil.');
    }

    public function logout(Request $request): RedirectResponse
    {
        $authUser = $request->session()->get('auth_user');
        if (is_array($authUser)) {
            $this->writeActivityLog([
                'user_id' => isset($authUser['id']) ? (int) $authUser['id'] : null,
                'name' => (string) ($authUser['name'] ?? '-'),
                'username' => (string) ($authUser['username'] ?? '-'),
                'ip_address' => $request->ip(),
                'longitude' => isset($authUser['longitude']) ? (string) $authUser['longitude'] : null,
                'latitude' => isset($authUser['latitude']) ? (string) $authUser['latitude'] : null,
                'action' => 'Logout',
                'detail' => 'User logout dari sistem.',
            ]);
        }

        $request->session()->forget('auth_user');
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }

    private function resolveUserDisplayName(LegacyUser $user): string
    {
        if (! empty($user->full_name)) {
            return (string) $user->full_name;
        }

        if (Schema::hasTable('employer') && Schema::hasColumn('employer', 'userid')) {
            $employer = DB::table('employer')
                ->where('userid', (int) $user->userid)
                ->first();

            if ($employer) {
                foreach (['full_name', 'name', 'fullname', 'employee_name'] as $column) {
                    if (isset($employer->{$column}) && trim((string) $employer->{$column}) !== '') {
                        return (string) $employer->{$column};
                    }
                }
            }
        }

        return (string) $user->username;
    }

    private function writeActivityLog(array $payload): void
    {
        if (! Schema::hasTable('activity_logs')) {
            return;
        }

        DB::table('activity_logs')->insert([
            'user_id' => $payload['user_id'] ?? null,
            'name' => Str::limit((string) ($payload['name'] ?? '-'), 255),
            'username' => Str::limit((string) ($payload['username'] ?? '-'), 255),
            'ip_address' => Str::limit((string) ($payload['ip_address'] ?? '-'), 45),
            'longitude' => isset($payload['longitude']) ? (string) $payload['longitude'] : null,
            'latitude' => isset($payload['latitude']) ? (string) $payload['latitude'] : null,
            'action' => Str::limit((string) ($payload['action'] ?? '-'), 120),
            'detail' => (string) ($payload['detail'] ?? '-'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    private function storeOfflineCaptcha(Request $request): void
    {
        $left = random_int(1, 20);
        $right = random_int(1, 20);

        $request->session()->put('offline_captcha_question', sprintf('%d + %d', $left, $right));
        $request->session()->put('offline_captcha_answer', (string) ($left + $right));
    }

    private function rejectLogin(Request $request, string $message): RedirectResponse
    {
        $this->storeOfflineCaptcha($request);

        return back()
            ->withErrors(['username' => $message])
            ->onlyInput('username');
    }
}
