<?php

namespace App\Console\Commands;

use App\Models\GeneralSetting;
use Illuminate\Console\Command;

class ConfigureLocalMail extends Command
{
    protected $signature = 'toapp:configure-local-mail
                            {--host=mailpit : SMTP host}
                            {--port=1025 : SMTP port}
                            {--from=no-reply@toapp.local : Sender email address}';

    protected $description = 'Configure database mail settings for local Mailpit SMTP';

    public function handle(): int
    {
        $settings = GeneralSetting::first();

        if (!$settings) {
            $this->error('general_settings row not found. Run migrations/install first.');

            return self::FAILURE;
        }

        $settings->en = 1;
        $settings->email_from = $this->option('from');
        $settings->email_from_name = $settings->email_from_name ?: (gs('site_name') ?: 'TOAPP');
        $settings->mail_config = (object) [
            'name' => 'smtp',
            'host' => $this->option('host'),
            'port' => (string) $this->option('port'),
            'enc' => '',
            'username' => '',
            'password' => '',
        ];
        $settings->save();

        cache()->forget('GeneralSetting');

        $this->info('Local mail configured (SMTP → Mailpit).');
        $this->table(
            ['Setting', 'Value'],
            [
                ['mail_config.name', 'smtp'],
                ['mail_config.host', $this->option('host')],
                ['mail_config.port', $this->option('port')],
                ['email_from', $this->option('from')],
                ['email enabled (en)', '1'],
            ]
        );
        $this->newLine();
        $this->line('Open Mailpit UI: http://localhost:8025');
        $this->line('Then register at /check-insure/register to receive the OTP email.');

        return self::SUCCESS;
    }
}
