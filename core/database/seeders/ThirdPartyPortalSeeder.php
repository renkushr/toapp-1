<?php

namespace Database\Seeders;

use App\Models\ThirdPartyPortal;
use Illuminate\Database\Seeder;

class ThirdPartyPortalSeeder extends Seeder
{
    public function run(): void
    {
        ThirdPartyPortal::updateOrCreate(
            ['slug' => 'check-insure'],
            [
                'name' => 'Check Insure',
                'base_path' => '/check-insure',
                'status' => true,
            ]
        );
    }
}
