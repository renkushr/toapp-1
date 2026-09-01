<?php

namespace App\Console\Commands;

use Database\Seeders\AgentSeeder;
use Illuminate\Console\Command;

class SeedSampleAgents extends Command
{
    protected $signature = 'toapp:seed-agents {--assign-existing : Assign agents to existing users by matching ZIP}';

    protected $description = 'Seed sample agents with demo credentials and ZIP code coverage';

    public function handle(): int
    {
        $this->call('db:seed', ['--class' => AgentSeeder::class, '--force' => true]);

        $this->newLine();
        $this->info('Sample agents ready. Login at /agent');
        $this->table(
            ['Username', 'Password', 'License', 'Sample ZIP codes'],
            [
                ['agent1', 'Agent@12345', 'AG-DEMO-001', '90210, 90211, 90212'],
                ['agent2', 'Agent@12345', 'AG-DEMO-002', '10001, 10002, 10003'],
                ['agent3', 'Agent@12345', 'AG-DEMO-003', '60601, 60602, 60603'],
            ]
        );

        return self::SUCCESS;
    }
}
