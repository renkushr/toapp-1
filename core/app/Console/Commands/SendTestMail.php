<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class SendTestMail extends Command
{
    protected $signature = 'toapp:test-mail {email?}';

    protected $description = 'Send a test EVER_CODE email through the configured mail driver';

    public function handle(): int
    {
        $user = User::query()->where('registration_channel', 'third_party')->latest('id')->first();

        if (!$user && $this->argument('email')) {
            $user = new User();
            $user->email = $this->argument('email');
            $user->firstname = 'Test';
            $user->lastname = 'User';
            $user->username = 'testuser';
        }

        if (!$user) {
            $this->error('No user found. Pass an email or register a Check Insure account first.');

            return self::FAILURE;
        }

        if ($this->argument('email')) {
            $user->email = $this->argument('email');
        }

        try {
            notify($user, 'EVER_CODE', ['code' => '111111']);
            $this->info('Test email sent to ' . $user->email . '. Check Mailpit at http://localhost:8025');

            return self::SUCCESS;
        } catch (\Throwable $e) {
            $this->error($e->getMessage());

            return self::FAILURE;
        }
    }
}
