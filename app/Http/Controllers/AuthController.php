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

        return view('auth.login');
    }

    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
            'g-recaptcha-response' => ['required', 'string'],
            'latitude' => ['nullable', 'numeric', 'between:-90,90'],
            'longitude' => ['nullable', 'numeric', 'between:-180,180'],
        ], [
            'g-recaptcha-response.required' => 'Silakan selesaikan verifikasi reCAPTCHA terlebih dahulu.',
        ]);

        $captchaSecret = (string) config('services.recaptcha.secret_key');
        if ($captchaSecret === '') {
            return back()
                ->withErrors(['username' => 'Google reCAPTCHA belum dikonfigurasi di server.'])
                ->onlyInput('username');
        }

        try {
            $captchaVerification = Http::asForm()
                ->timeout(8)
                ->post('https://www.google.com/recaptcha/api/siteverify', [
                    'secret' => $captchaSecret,
                    'response' => (string) $credentials['g-recaptcha-response'],
                    'remoteip' => $request->ip(),
                ]);
        } catch (\Throwable $exception) {
            return back()
                ->withErrors(['username' => 'Gagal memverifikasi reCAPTCHA. Coba lagi sebentar.'])
                ->onlyInput('username');
        }

        if (! $captchaVerification->ok() || ! data_get($captchaVerification->json(), 'success', false)) {
            return back()
                ->withErrors(['username' => 'Verifikasi reCAPTCHA tidak valid. Silakan coba lagi.'])
                ->onlyInput('username');
        }

        $user = LegacyUser::query()
            ->where('username', $credentials['username'])
            ->first();

        if (! $user) {
            return back()
                ->withErrors(['username' => 'Username atau password salah.'])
                ->onlyInput('username');
        }

        $validPassword = Hash::check($credentials['password'], (string) $user->password)
            || hash_equals((string) $user->password, $credentials['password']);

        if (! $validPassword) {
            return back()
                ->withErrors(['username' => 'Username atau password salah.'])
                ->onlyInput('username');
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
}
