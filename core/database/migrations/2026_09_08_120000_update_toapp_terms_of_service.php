<?php

use App\Models\Frontend;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        $path = database_path('data/toapp_terms_of_service.html');
        $details = is_file($path) ? (string) file_get_contents($path) : '';

        if ($details === '') {
            return;
        }

        $policy = Frontend::query()
            ->where('data_keys', 'policy_pages.element')
            ->where('slug', 'terms-of-service')
            ->first();

        if (! $policy) {
            $policy = new Frontend();
            $policy->data_keys = 'policy_pages.element';
            $policy->slug = 'terms-of-service';
            $policy->tempname = 'basic';
        }

        $values = (array) ($policy->data_values ?? []);
        $values['title'] = 'To-App Terms & Conditions';
        $values['details'] = $details;
        $policy->data_values = $values;
        $policy->save();
    }

    public function down(): void
    {
        // Content update is not reversed.
    }
};
