<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('insurance_applications', function (Blueprint $table) {
            if (!Schema::hasColumn('insurance_applications', 'payment_monthly')) {
                $table->decimal('payment_monthly', 12, 2)->nullable()->after('quoted_annual');
            }

            if (!Schema::hasColumn('insurance_applications', 'coverage_amount')) {
                $table->unsignedInteger('coverage_amount')->nullable()->after('payment_monthly');
            }
        });
    }

    public function down(): void
    {
        Schema::table('insurance_applications', function (Blueprint $table) {
            $drop = [];

            if (Schema::hasColumn('insurance_applications', 'payment_monthly')) {
                $drop[] = 'payment_monthly';
            }

            if (Schema::hasColumn('insurance_applications', 'coverage_amount')) {
                $drop[] = 'coverage_amount';
            }

            if ($drop) {
                $table->dropColumn($drop);
            }
        });
    }
};
