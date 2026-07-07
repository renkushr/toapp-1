<?php

namespace Database\Seeders;

use App\Constants\Status;
use App\Models\Plan;
use App\Models\ThirdPartyPortal;
use Illuminate\Database\Seeder;

class SampleInsuranceProductSeeder extends Seeder
{
    public function run(): void
    {
        $portal = ThirdPartyPortal::firstOrCreate(
            ['slug' => 'check-insure'],
            [
                'name' => 'Check Insure',
                'base_path' => '/check-insure',
                'status' => true,
            ]
        );

        $products = [
            [
                'name' => 'Term Life Essential',
                'category' => 'Term Life',
                'descript' => 'Affordable term coverage for healthy non-smokers ages 18–55.',
                'agefrom' => 18,
                'ageto' => 55,
                'sex' => '0',
                'health' => '1',
                'nicotin' => '0',
                'allows_chronic' => false,
                'monthly' => [45, 55, 65, 80, 95, 110, 130, 150],
                'annual' => [480, 580, 690, 850, 990, 1150, 1350, 1560],
            ],
            [
                'name' => 'Term Life Flex',
                'category' => 'Term Life',
                'descript' => 'Flexible term plan for ages 18–65 including smokers with fair health.',
                'agefrom' => 18,
                'ageto' => 65,
                'sex' => '0',
                'health' => '0',
                'nicotin' => '2',
                'allows_chronic' => true,
                'monthly' => [60, 72, 88, 105, 125, 145, 170, 195],
                'annual' => [650, 780, 950, 1120, 1330, 1540, 1810, 2080],
            ],
            [
                'name' => 'Whole Life Secure',
                'category' => 'Whole Life',
                'descript' => 'Lifetime protection with level premiums for ages 25–60.',
                'agefrom' => 25,
                'ageto' => 60,
                'sex' => '0',
                'health' => '1',
                'nicotin' => '0',
                'allows_chronic' => false,
                'monthly' => [95, 110, 130, 155, 180, 210, 245, 280],
                'annual' => [1020, 1180, 1390, 1660, 1920, 2250, 2620, 2990],
            ],
            [
                'name' => 'Whole Life Premier',
                'category' => 'Whole Life',
                'descript' => 'Enhanced whole life for applicants with chronic conditions accepted.',
                'agefrom' => 30,
                'ageto' => 70,
                'sex' => '0',
                'health' => '0',
                'nicotin' => '2',
                'allows_chronic' => true,
                'monthly' => [120, 140, 165, 195, 230, 270, 315, 360],
                'annual' => [1290, 1500, 1770, 2090, 2460, 2890, 3370, 3850],
            ],
            [
                'name' => 'IUL Growth',
                'category' => 'IUL',
                'descript' => 'Indexed universal life for growth-focused buyers ages 18–50.',
                'agefrom' => 18,
                'ageto' => 50,
                'sex' => '0',
                'health' => '1',
                'nicotin' => '2',
                'allows_chronic' => false,
                'monthly' => [150, 175, 205, 240, 280, 325, 375, 430],
                'annual' => [1620, 1890, 2210, 2580, 3010, 3490, 4030, 4620],
            ],
            [
                'name' => 'IUL Legacy',
                'category' => 'IUL',
                'descript' => 'Long-term IUL for ages 35–65 with fair health history.',
                'agefrom' => 35,
                'ageto' => 65,
                'sex' => '0',
                'health' => '2',
                'nicotin' => '2',
                'allows_chronic' => true,
                'monthly' => [180, 210, 245, 285, 330, 385, 445, 510],
                'annual' => [1940, 2260, 2640, 3070, 3550, 4140, 4780, 5480],
            ],
            [
                'name' => 'Women Term Advantage',
                'category' => 'Term Life',
                'descript' => 'Term life designed for female applicants ages 18–55 in good health.',
                'agefrom' => 18,
                'ageto' => 55,
                'sex' => '2',
                'health' => '1',
                'nicotin' => '0',
                'allows_chronic' => false,
                'monthly' => [40, 48, 58, 70, 82, 96, 112, 128],
                'annual' => [430, 515, 620, 750, 880, 1030, 1200, 1370],
            ],
            [
                'name' => 'Men Term Advantage',
                'category' => 'Term Life',
                'descript' => 'Term life designed for male applicants ages 18–55 in good health.',
                'agefrom' => 18,
                'ageto' => 55,
                'sex' => '1',
                'health' => '1',
                'nicotin' => '0',
                'allows_chronic' => false,
                'monthly' => [42, 50, 62, 74, 88, 102, 118, 135],
                'annual' => [450, 540, 665, 795, 945, 1095, 1270, 1450],
            ],
        ];

        foreach ($products as $data) {
            $plan = Plan::updateOrCreate(
                ['name' => $data['name']],
                array_merge($this->basePlanFields(), [
                    'category' => $data['category'],
                    'descript' => $data['descript'],
                    'agefrom' => (string) $data['agefrom'],
                    'ageto' => (string) $data['ageto'],
                    'sex' => (string) $data['sex'],
                    'health' => (string) $data['health'],
                    'nicotin' => (string) $data['nicotin'],
                    'allows_chronic' => $data['allows_chronic'],
                    'monthprice' => $data['monthly'][0],
                    'annualprice' => $data['annual'][0],
                    'term' => $this->termsHtml($data['name'], $data['category']),
                    'status' => Status::ENABLE,
                ])
            );

            for ($i = 1; $i <= 8; $i++) {
                $plan->{"monthprice{$i}"} = $data['monthly'][$i - 1];
                $plan->{"annualprice{$i}"} = $data['annual'][$i - 1];
            }

            $plan->save();

            $plan->thirdPartyPortals()->sync([
                $portal->id => ['published' => true],
            ]);
        }
    }

    protected function basePlanFields(): array
    {
        return [
            'min_amount' => 100,
            'max_amount' => 50000,
            'total_return' => 1,
            'interest_type' => 2,
            'interest' => 0,
        ];
    }

    protected function termsHtml(string $name, string $category): string
    {
        return <<<HTML
<h3>{$name}</h3>
<p><strong>Category:</strong> {$category}</p>
<p>This is a sample insurance product for demonstration on Check Insure. Coverage terms, exclusions, and final premium are confirmed by your licensed agent after application review.</p>
<ul>
<li>Death benefit paid to named beneficiaries during the policy term</li>
<li>Premium based on age, gender, health, smoking status, and selected price range</li>
<li>Payment is completed outside this website; your agent confirms activation</li>
</ul>
HTML;
    }
}
