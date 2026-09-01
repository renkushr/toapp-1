<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('insurance_applications', function (Blueprint $table) {
            if (!Schema::hasColumn('insurance_applications', 'coverage_length')) {
                $table->unsignedTinyInteger('coverage_length')->nullable()->after('coverage_amount');
            }
        });
    }

    public function down(): void
    {
        Schema::table('insurance_applications', function (Blueprint $table) {
            if (Schema::hasColumn('insurance_applications', 'coverage_length')) {
                $table->dropColumn('coverage_length');
            }
        });
    }
};
