<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('general_settings') && Schema::hasColumn('general_settings', 'site_name')) {
            DB::statement("UPDATE general_settings SET site_name = REPLACE(REPLACE(site_name, 'TO-APP', 'To-App'), 'To-app', 'To-App')");
        }

        if (Schema::hasTable('general_settings') && Schema::hasColumn('general_settings', 'email_from_name')) {
            DB::statement("UPDATE general_settings SET email_from_name = REPLACE(REPLACE(email_from_name, 'TO-APP', 'To-App'), 'To-app', 'To-App')");
        }

        if (Schema::hasTable('frontends') && Schema::hasColumn('frontends', 'data_values')) {
            DB::statement("UPDATE frontends SET data_values = REPLACE(REPLACE(data_values, 'TO-APP', 'To-App'), 'To-app', 'To-App') WHERE data_values LIKE '%To-app%' OR data_values LIKE '%TO-APP%'");
        }

        if (Schema::hasTable('cms_pages')) {
            if (Schema::hasColumn('cms_pages', 'title')) {
                DB::statement("UPDATE cms_pages SET title = REPLACE(REPLACE(title, 'TO-APP', 'To-App'), 'To-app', 'To-App')");
            }
            if (Schema::hasColumn('cms_pages', 'body')) {
                DB::statement("UPDATE cms_pages SET body = REPLACE(REPLACE(body, 'TO-APP', 'To-App'), 'To-app', 'To-App')");
            }
        }

        Cache::forget('GeneralSetting');
    }

    public function down(): void
    {
        // Irreversible brand casing normalization.
    }
};
