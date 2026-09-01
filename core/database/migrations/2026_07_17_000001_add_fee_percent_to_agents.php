<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('agents', 'fee_percent')) {
            Schema::table('agents', function (Blueprint $table) {
                $table->decimal('fee_percent', 5, 2)->default(0)->after('license_no')
                    ->comment('Platform fee % the agent pays per product sale. Admin-managed only.');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('agents', 'fee_percent')) {
            Schema::table('agents', function (Blueprint $table) {
                $table->dropColumn('fee_percent');
            });
        }
    }
};
