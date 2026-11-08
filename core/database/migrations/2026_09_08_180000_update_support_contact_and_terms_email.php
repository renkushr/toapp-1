<?php

use App\Models\Frontend;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('general_settings')) {
            $payload = [
                'support_email' => 'tonkowtek@to-app.com',
                'updated_at' => now(),
            ];

            if (Schema::hasColumn('general_settings', 'support_phone')) {
                $payload['support_phone'] = null;
            }

            DB::table('general_settings')->update($payload);
        }

        $path = database_path('data/toapp_terms_of_service.html');
        $details = is_file($path) ? (string) file_get_contents($path) : '';
        if ($details === '' || ! Schema::hasTable('frontends')) {
            return;
        }

        $policy = Frontend::query()
            ->where('data_keys', 'policy_pages.element')
            ->where('slug', 'terms-of-service')
            ->first();

        if (! $policy) {
            return;
        }

        $values = (array) ($policy->data_values ?? []);
        $values['title'] = $values['title'] ?? 'To-App Terms & Conditions';
        $values['details'] = $details;
        $policy->data_values = $values;
        $policy->save();
    }

    public function down(): void
    {
        // Contact details update is not reversed.
    }
};
