<?php

namespace App\Http\Controllers\ToappAdmin;

use App\Models\GeneralSetting;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class SettingController extends Controller
{
    public function edit()
    {
        return view('toapp_admin.settings.edit', [
            'pageTitle' => __('Settings'),
            'settings' => GeneralSetting::first() ?? $this->defaultSettings(),
        ]);
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'site_name' => ['required', 'string', 'max:40'],
            'cur_text' => ['required', 'string', 'max:40'],
            'cur_sym' => ['required', 'string', 'max:40'],
            'email_from' => ['nullable', 'email', 'max:40'],
            'email_from_name' => ['nullable', 'string', 'max:255'],
            'support_phone' => ['nullable', 'string', 'max:60'],
            'support_email' => ['nullable', 'email', 'max:120'],
            'paginate_number' => ['required', 'integer', 'min:5', 'max:100'],
            'registration' => ['nullable', 'boolean'],
            'ev' => ['nullable', 'boolean'],
            'user_approval' => ['nullable', 'boolean'],
            'sv' => ['nullable', 'boolean'],
            'kv' => ['nullable', 'boolean'],
            'maintenance_mode' => ['nullable', 'boolean'],
            'secure_password' => ['nullable', 'boolean'],
            'en' => ['nullable', 'boolean'],
        ]);

        $settings = GeneralSetting::firstOrNew(['id' => 1]);
        $settings->site_name = $validated['site_name'];
        $settings->cur_text = $validated['cur_text'];
        $settings->cur_sym = $validated['cur_sym'];
        $settings->email_from = $validated['email_from'] ?? null;
        $settings->email_from_name = $validated['email_from_name'] ?? null;
        $settings->support_phone = $validated['support_phone'] ?? null;
        $settings->support_email = $validated['support_email'] ?? null;
        $settings->paginate_number = $validated['paginate_number'];
        $settings->registration = (int) ($validated['registration'] ?? 0);
        $settings->ev = (int) ($validated['ev'] ?? 0);
        $settings->user_approval = (int) ($validated['user_approval'] ?? 0);
        $settings->sv = (int) ($validated['sv'] ?? 0);
        $settings->kv = (int) ($validated['kv'] ?? 0);
        $settings->maintenance_mode = (int) ($validated['maintenance_mode'] ?? 0);
        $settings->secure_password = (int) ($validated['secure_password'] ?? 0);
        $settings->en = (int) ($validated['en'] ?? 0);
        $settings->save();
        cache()->forget('GeneralSetting');

        return back()->with('status', 'Settings updated successfully.');
    }

    public function email()
    {
        $settings = GeneralSetting::first() ?? $this->defaultSettings();
        $mail = $settings->mail_config;
        if (is_string($mail)) {
            $mail = json_decode($mail);
        }
        $mail = (object) ($mail ?: ['name' => 'smtp']);

        return view('toapp_admin.settings.email', [
            'pageTitle' => __('Email settings'),
            'settings' => $settings,
            'mail' => $mail,
        ]);
    }

    public function updateEmail(Request $request)
    {
        $request->validate([
            'email_method' => ['required', 'in:php,smtp,sendgrid,mailjet'],
            'email_from' => ['nullable', 'email', 'max:120'],
            'email_from_name' => ['nullable', 'string', 'max:255'],
            'en' => ['nullable', 'boolean'],
            'host' => ['required_if:email_method,smtp', 'nullable', 'string', 'max:120'],
            'port' => ['required_if:email_method,smtp', 'nullable', 'string', 'max:10'],
            'username' => ['required_if:email_method,smtp', 'nullable', 'string', 'max:120'],
            'password' => ['required_if:email_method,smtp', 'nullable', 'string', 'max:255'],
            'enc' => ['required_if:email_method,smtp', 'nullable', 'in:ssl,tls'],
            'appkey' => ['required_if:email_method,sendgrid', 'nullable', 'string', 'max:255'],
            'public_key' => ['required_if:email_method,mailjet', 'nullable', 'string', 'max:255'],
            'secret_key' => ['required_if:email_method,mailjet', 'nullable', 'string', 'max:255'],
        ], [
            'host.required_if' => 'SMTP host is required.',
            'port.required_if' => 'SMTP port is required.',
            'username.required_if' => 'SMTP username is required.',
            'password.required_if' => 'SMTP password is required.',
            'enc.required_if' => 'SMTP encryption is required.',
            'appkey.required_if' => 'SendGrid app key is required.',
            'public_key.required_if' => 'Mailjet public key is required.',
            'secret_key.required_if' => 'Mailjet secret key is required.',
        ]);

        $method = $request->email_method;
        if ($method === 'php') {
            $data = ['name' => 'php'];
        } elseif ($method === 'smtp') {
            $host = trim((string) $request->host);
            $username = trim((string) $request->username);
            // Google App Passwords often paste with normal/non-breaking spaces between groups.
            $password = preg_replace('/[\s\x{00A0}\x{2000}-\x{200B}\x{202F}\x{205F}\x{3000}]+/u', '', (string) $request->password);

            if (str_contains(strtolower($host), 'gmail.com')) {
                if (!preg_match('/^[a-z]{16}$/i', $password)) {
                    return back()->withErrors([
                        'password' => 'Gmail requires a 16-character App Password (letters only, no spaces). Do not use your regular Gmail password. Create one at Google Account → Security → App passwords.',
                    ])->withInput();
                }

                $emailFrom = strtolower(trim((string) $request->email_from));
                if ($emailFrom !== '' && $emailFrom !== strtolower($username)) {
                    return back()->withErrors([
                        'email_from' => 'For Gmail SMTP, the "Email from address" must be the same as the SMTP username.',
                    ])->withInput();
                }
            }

            $data = [
                'name' => 'smtp',
                'host' => $host,
                'port' => trim((string) $request->port),
                'enc' => $request->enc,
                'username' => $username,
                'password' => $password,
            ];
        } elseif ($method === 'sendgrid') {
            $data = [
                'name' => 'sendgrid',
                'appkey' => trim((string) $request->appkey),
            ];
        } else {
            $data = [
                'name' => 'mailjet',
                'public_key' => trim((string) $request->public_key),
                'secret_key' => trim((string) $request->secret_key),
            ];
        }

        $settings = GeneralSetting::firstOrNew(['id' => 1]);
        $settings->mail_config = $data;
        $settings->en = (int) $request->boolean('en');
        if ($method === 'smtp' && str_contains(strtolower($data['host']), 'gmail.com')) {
            $settings->email_from = $data['username'];
        } elseif ($request->filled('email_from')) {
            $settings->email_from = $request->email_from;
        }
        if ($request->filled('email_from_name')) {
            $settings->email_from_name = $request->email_from_name;
        }
        $settings->save();
        cache()->forget('GeneralSetting');

        return back()->with('status', 'Email settings saved successfully.');
    }

    public function testEmail(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        if (!gs('en')) {
            return back()->withErrors(['email' => 'Turn on “Enable email sending” before testing.']);
        }

        $config = gs('mail_config');
        $receiverName = explode('@', $request->email)[0];
        $subject = strtoupper($config->name ?? 'SMTP') . ' Configuration Success';
        $message = 'Your email notification setting is configured successfully for ' . gs('site_name');

        $user = [
            'username' => $request->email,
            'email' => $request->email,
            'fullname' => $receiverName,
        ];

        notify($user, 'DEFAULT', [
            'subject' => $subject,
            'message' => $message,
        ], ['email'], false);

        if (session('mail_error')) {
            $error = (string) session('mail_error');
            if (stripos($error, 'authenticate') !== false) {
                $config = gs('mail_config');
                if (($config->name ?? '') === 'smtp' && str_contains(strtolower($config->host ?? ''), 'gmail.com')) {
                    $error .= ' For Gmail: use a 16-character App Password (not your login password), and set Email from = your Gmail address.';
                }
            }

            return back()->withErrors(['email' => $error])->withInput();
        }

        return back()->with('status', 'Test email sent to ' . $request->email . ' successfully.');
    }

    private function defaultSettings(): GeneralSetting
    {
        return new GeneralSetting([
            'site_name' => 'To-App',
            'cur_text' => 'USD',
            'cur_sym' => '$',
            'paginate_number' => 20,
            'registration' => 1,
            'ev' => 1,
            'user_approval' => 0,
            'sv' => 0,
            'kv' => 0,
            'en' => 0,
            'maintenance_mode' => 0,
            'secure_password' => 0,
            'mail_config' => (object) ['name' => 'smtp'],
        ]);
    }
}
