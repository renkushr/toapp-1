<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('general_settings', 'user_approval')) {
            Schema::table('general_settings', function (Blueprint $table) {
                $table->boolean('user_approval')->default(false)->after('ev');
            });
        }

        if (\Illuminate\Support\Facades\Schema::hasTable('general_settings')) {
            \Illuminate\Support\Facades\DB::table('general_settings')
                ->where('id', 1)
                ->update([
                    'ev' => 1,
                    'user_approval' => 0,
                ]);
        }
    }

    public function down(): void
    {
        Schema::table('general_settings', function (Blueprint $table) {
            if (Schema::hasColumn('general_settings', 'user_approval')) {
                $table->dropColumn('user_approval');
            }
        });
    }
};
