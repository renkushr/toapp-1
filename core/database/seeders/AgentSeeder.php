<?php

namespace Database\Seeders;

use App\Models\Agent;
use App\Models\AgentZipCode;
use App\Models\User;
use App\Support\UsZip;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AgentSeeder extends Seeder
{
    public function run(): void
    {
        $samples = [
            [
                'name' => 'John Smith',
                'email' => 'agent1@toapp.local',
                'username' => 'agent1',
                'license_no' => 'AG-DEMO-001',
                'password' => 'Agent@12345',
                'zip_codes' => ['90210', '90211', '90212', '90001', '90002'],
            ],
            [
                'name' => 'Jane Doe',
                'email' => 'agent2@toapp.local',
                'username' => 'agent2',
                'license_no' => 'AG-DEMO-002',
                'password' => 'Agent@12345',
                'zip_codes' => ['10001', '10002', '10003', '10004', '10005'],
            ],
            [
                'name' => 'Mike Johnson',
                'email' => 'agent3@toapp.local',
                'username' => 'agent3',
                'license_no' => 'AG-DEMO-003',
                'password' => 'Agent@12345',
                'zip_codes' => ['60601', '60602', '60603', '60604', '60605'],
            ],
        ];

        foreach ($samples as $sample) {
            $agent = Agent::updateOrCreate(
                ['license_no' => $sample['license_no']],
                [
                    'name' => $sample['name'],
                    'email' => $sample['email'],
                    'username' => $sample['username'],
                    'password' => Hash::make($sample['password']),
                    'status' => true,
                ]
            );

            $agent->zipCodes()->delete();

            foreach ($sample['zip_codes'] as $zip) {
                AgentZipCode::create([
                    'agent_id' => $agent->id,
                    'zip_code' => $zip,
                ]);
            }
        }

        $this->assignExistingUsersByZip();
    }

    protected function assignExistingUsersByZip(): int
    {
        $assigned = 0;

        User::query()
            ->whereNull('agent_id')
            ->whereNotNull('zipcode')
            ->where('zipcode', '!=', '')
            ->orderBy('id')
            ->chunkById(100, function ($users) use (&$assigned) {
                foreach ($users as $user) {
                    $zip = UsZip::normalize($user->zipcode);
                    if (!$zip) {
                        continue;
                    }

                    $zipRow = AgentZipCode::query()
                        ->where('zip_code', $zip)
                        ->whereHas('agent', fn ($q) => $q->where('status', true))
                        ->with('agent')
                        ->first();

                    if (!$zipRow?->agent) {
                        continue;
                    }

                    $user->agent_id = $zipRow->agent_id;
                    $user->agent_license_no = $zipRow->agent->license_no;
                    $user->zipcode = $zip;
                    $user->save();
                    $assigned++;
                }
            });

        return $assigned;
    }
}
