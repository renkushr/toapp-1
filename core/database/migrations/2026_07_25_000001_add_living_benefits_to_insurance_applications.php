<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('insurance_applications', function (Blueprint $table) {
            if (!Schema::hasColumn('insurance_applications', 'living_benefits')) {
                $table->boolean('living_benefits')->default(false)->after('coverage_length');
            }
        });
    }

    public function down(): void
    {
        Schema::table('insurance_applications', function (Blueprint $table) {
            if (Schema::hasColumn('insurance_applications', 'living_benefits')) {
                $table->dropColumn('living_benefits');
            }
        });
    }
};
