<?php

namespace App\Console\Commands;

use Database\Seeders\SampleInsuranceProductSeeder;
use Database\Seeders\ThirdPartyPortalSeeder;
use Illuminate\Console\Command;

class SeedSampleProducts extends Command
{
    protected $signature = 'toapp:seed-products';

    protected $description = 'Seed sample insurance products published to Check Insure';

    public function handle(): int
    {
        $this->call('db:seed', ['--class' => ThirdPartyPortalSeeder::class, '--force' => true]);
        $this->call('db:seed', ['--class' => SampleInsuranceProductSeeder::class, '--force' => true]);

        $this->newLine();
        $this->info('8 sample products published to Check Insure (/check-insure).');
        $this->table(
            ['Product', 'Category', 'Age', 'Notes'],
            [
                ['Term Life Essential', 'Term Life', '18–55', 'Non-smoker, good health'],
                ['Term Life Flex', 'Term Life', '18–65', 'Smokers OK, chronic OK'],
                ['Whole Life Secure', 'Whole Life', '25–60', 'Non-smoker, good health'],
                ['Whole Life Premier', 'Whole Life', '30–70', 'Chronic conditions accepted'],
                ['IUL Growth', 'IUL', '18–50', 'Good health'],
                ['IUL Legacy', 'IUL', '35–65', 'Fair health, chronic OK'],
                ['Women Term Advantage', 'Term Life', '18–55', 'Female only'],
                ['Men Term Advantage', 'Term Life', '18–55', 'Male only'],
            ]
        );
        $this->line('Test at: /check-insure/register → use ZIP 90210, 10001, or 60601 with seeded agents.');

        return self::SUCCESS;
    }
}
