<?php

namespace App\Http\Controllers;

use App\Models\LegacyUser;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Illuminate\View\View;

class AuthController extends Controller
{
    private const OTP_SESSION_KEY = 'login_otp_pending';

    public function showLogin(Request $request): View|RedirectResponse
    {
        if ($request->session()->has('auth_user')) {
            return redirect('/dashboard');
        }

        $pending = $request->session()->get(self::OTP_SESSION_KEY);
        if (is_array($pending)) {
            $expiresAt = isset($pending['expires_at']) ? (int) $pending['expires_at'] : 0;
            if ($expiresAt <= 0 || $expiresAt < time()) {
                $request->session()->forget(self::OTP_SESSION_KEY);
            }
        }

        $this->storeOfflineCaptcha($request);

        return view('auth.login');
    }

    public function showOtpLogin(Request $request): View|RedirectResponse
    {
        if ($request->session()->has('auth_user')) {
            return redirect('/dashboard');
        }

        $pending = $request->session()->get(self::OTP_SESSION_KEY);
        if (is_array($pending)) {
            $expiresAt = isset($pending['expires_at']) ? (int) $pending['expires_at'] : 0;
            if ($expiresAt <= 0 || $expiresAt < time()) {
                $request->session()->forget(self::OTP_SESSION_KEY);
            }
        }

        $this->storeOfflineCaptcha($request);

        return view('auth.login-otp');
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

    public function requestOtp(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email', 'max:255'],
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

        $inputEmail = trim(strtolower((string) $credentials['email']));
        $user = $this->findUserByEmail($inputEmail);

        if (! $user) {
            $this->storeOfflineCaptcha($request);
            return back()
                ->withErrors(['email' => 'Email tidak terdaftar di sistem.'])
                ->onlyInput('email');
        }

        $displayName = $this->resolveUserDisplayName($user);
        $email = $this->resolveUserEmail($user);
        if ($email === null) {
            return $this->rejectLogin($request, 'Email user belum tersedia. Hubungi admin untuk melengkapi email.');
        }

        $latitude = isset($credentials['latitude']) && $credentials['latitude'] !== ''
            ? (string) $credentials['latitude']
            : null;
        $longitude = isset($credentials['longitude']) && $credentials['longitude'] !== ''
            ? (string) $credentials['longitude']
            : null;

        $otpCode = (string) random_int(100000, 999999);
        $request->session()->put(self::OTP_SESSION_KEY, [
            'id' => (int) $user->userid,
            'username' => $user->username,
            'levelid' => (int) $user->levelid,
            'name' => $displayName,
            'email' => $email,
            'latitude' => $latitude,
            'longitude' => $longitude,
            'otp_hash' => Hash::make($otpCode),
            'expires_at' => time() + 300,
            'attempts' => 0,
        ]);

        try {
            Mail::raw(
                "Kode OTP login Anda: {$otpCode}\n\nKode berlaku 5 menit. Jangan bagikan kode ini kepada siapa pun.",
                function ($message) use ($email): void {
                    $message->to($email)
                        ->subject('OTP Login Dashboard Kuburan');
                }
            );
        } catch (\Throwable $exception) {
            $request->session()->forget(self::OTP_SESSION_KEY);
            return $this->rejectLogin($request, 'Gagal mengirim OTP ke email. Coba lagi sebentar.');
        }

        $this->storeOfflineCaptcha($request);

        return back()
            ->with('status', 'Kode OTP sudah dikirim ke email: ' . $this->maskEmail($email))
            ->withInput(['email' => $email]);
    }

    public function verifyOtp(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'otp_code' => ['required', 'digits:6'],
        ]);

        $pending = $request->session()->get(self::OTP_SESSION_KEY);
        if (! is_array($pending)) {
            return $this->rejectLogin($request, 'Sesi OTP tidak ditemukan. Silakan login ulang.');
        }

        $expiresAt = isset($pending['expires_at']) ? (int) $pending['expires_at'] : 0;
        if ($expiresAt <= 0 || $expiresAt < time()) {
            $request->session()->forget(self::OTP_SESSION_KEY);
            return $this->rejectLogin($request, 'Kode OTP sudah kedaluwarsa. Silakan login ulang.');
        }

        $attempts = (int) ($pending['attempts'] ?? 0);
        if ($attempts >= 5) {
            $request->session()->forget(self::OTP_SESSION_KEY);
            return $this->rejectLogin($request, 'Percobaan OTP melebihi batas. Silakan login ulang.');
        }

        $otpHash = (string) ($pending['otp_hash'] ?? '');
        if ($otpHash === '' || ! Hash::check((string) $validated['otp_code'], $otpHash)) {
            $pending['attempts'] = $attempts + 1;
            $request->session()->put(self::OTP_SESSION_KEY, $pending);
            return back()->withErrors(['otp_code' => 'Kode OTP tidak valid.']);
        }

        $user = LegacyUser::query()->find((int) ($pending['id'] ?? 0));
        if (! $user) {
            $request->session()->forget(self::OTP_SESSION_KEY);
            return $this->rejectLogin($request, 'User tidak ditemukan. Silakan login ulang.');
        }

        $request->session()->regenerate();
        $request->session()->put('auth_user', [
            'id' => (int) $user->userid,
            'username' => $user->username,
            'levelid' => (int) $user->levelid,
            'name' => (string) ($pending['name'] ?? $user->username),
            'latitude' => isset($pending['latitude']) ? (string) $pending['latitude'] : null,
            'longitude' => isset($pending['longitude']) ? (string) $pending['longitude'] : null,
        ]);
        $request->session()->forget(self::OTP_SESSION_KEY);

        $this->writeActivityLog([
            'user_id' => (int) $user->userid,
            'name' => (string) ($pending['name'] ?? $user->username),
            'username' => $user->username,
            'ip_address' => $request->ip(),
            'longitude' => isset($pending['longitude']) ? (string) $pending['longitude'] : null,
            'latitude' => isset($pending['latitude']) ? (string) $pending['latitude'] : null,
            'action' => 'Login',
            'detail' => 'User login ke sistem via OTP email.',
        ]);

        return redirect('/dashboard')
            ->with('status', 'Login berhasil.');
    }

    public function resendOtp(Request $request): RedirectResponse
    {
        $pending = $request->session()->get(self::OTP_SESSION_KEY);
        if (! is_array($pending)) {
            return $this->rejectLogin($request, 'Sesi OTP tidak ditemukan. Silakan login ulang.');
        }

        $email = isset($pending['email']) ? trim((string) $pending['email']) : '';
        if ($email === '') {
            $request->session()->forget(self::OTP_SESSION_KEY);
            return $this->rejectLogin($request, 'Email OTP tidak ditemukan. Silakan login ulang.');
        }

        $otpCode = (string) random_int(100000, 999999);
        $pending['otp_hash'] = Hash::make($otpCode);
        $pending['expires_at'] = time() + 300;
        $pending['attempts'] = 0;
        $request->session()->put(self::OTP_SESSION_KEY, $pending);

        try {
            Mail::raw(
                "Kode OTP login Anda: {$otpCode}\n\nKode berlaku 5 menit. Jangan bagikan kode ini kepada siapa pun.",
                function ($message) use ($email): void {
                    $message->to($email)
                        ->subject('OTP Login Dashboard Kuburan');
                }
            );
        } catch (\Throwable $exception) {
            return back()->withErrors(['otp_code' => 'Gagal mengirim ulang OTP. Coba lagi.']);
        }

        return back()->with('status', 'OTP baru sudah dikirim ke email: ' . $this->maskEmail($email));
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
            ->onlyInput(['username', 'email']);
    }

    private function resolveUserEmail(LegacyUser $user): ?string
    {
        $email = trim((string) ($user->email ?? ''));
        if ($email !== '') {
            return $email;
        }

        if (Schema::hasTable('employer') && Schema::hasColumn('employer', 'userid')) {
            $employer = DB::table('employer')
                ->where('userid', (int) $user->userid)
                ->first();

            if ($employer) {
                foreach (['email', 'email_address', 'mail'] as $column) {
                    if (isset($employer->{$column}) && trim((string) $employer->{$column}) !== '') {
                        return trim((string) $employer->{$column});
                    }
                }
            }
        }

        return null;
    }

    private function maskEmail(string $email): string
    {
        $email = trim($email);
        if ($email === '' || ! str_contains($email, '@')) {
            return '***';
        }

        [$local, $domain] = explode('@', $email, 2);
        $localMasked = strlen($local) <= 2
            ? substr($local, 0, 1) . '*'
            : substr($local, 0, 2) . str_repeat('*', max(1, strlen($local) - 2));

        return $localMasked . '@' . $domain;
    }

    private function findUserByEmail(string $email): ?LegacyUser
    {
        if ($email === '') {
            return null;
        }

        if (Schema::hasTable('user') && Schema::hasColumn('user', 'email')) {
            $user = LegacyUser::query()
                ->whereRaw('LOWER(email) = ?', [$email])
                ->first();
            if ($user) {
                return $user;
            }
        }

        if (! Schema::hasTable('employer') || ! Schema::hasColumn('employer', 'userid')) {
            return null;
        }

        $employerColumns = Schema::getColumnListing('employer');
        $emailColumn = null;
        foreach (['email', 'email_address', 'mail'] as $candidate) {
            if (in_array($candidate, $employerColumns, true)) {
                $emailColumn = $candidate;
                break;
            }
        }
        if ($emailColumn === null) {
            return null;
        }

        $userId = DB::table('employer')
            ->whereRaw('LOWER(' . $emailColumn . ') = ?', [$email])
            ->value('userid');
        if (! $userId) {
            return null;
        }

        return LegacyUser::query()->find((int) $userId);
    }
}
