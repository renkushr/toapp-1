<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('users')) {
            return;
        }

        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'payout_method')) {
                $table->string('payout_method', 20)->nullable()->after('zipcode');
            }
            if (!Schema::hasColumn('users', 'payout_paypal_email')) {
                $table->string('payout_paypal_email', 120)->nullable()->after('payout_method');
            }
            if (!Schema::hasColumn('users', 'payout_bank_name')) {
                $table->string('payout_bank_name', 120)->nullable()->after('payout_paypal_email');
            }
            if (!Schema::hasColumn('users', 'payout_account_name')) {
                $table->string('payout_account_name', 120)->nullable()->after('payout_bank_name');
            }
            if (!Schema::hasColumn('users', 'payout_account_number')) {
                $table->string('payout_account_number', 60)->nullable()->after('payout_account_name');
            }
            if (!Schema::hasColumn('users', 'payout_routing_number')) {
                $table->string('payout_routing_number', 40)->nullable()->after('payout_account_number');
            }
        });
    }

    public function down(): void
    {
        if (!Schema::hasTable('users')) {
            return;
        }

        Schema::table('users', function (Blueprint $table) {
            foreach ([
                'payout_method',
                'payout_paypal_email',
                'payout_bank_name',
                'payout_account_name',
                'payout_account_number',
                'payout_routing_number',
            ] as $column) {
                if (Schema::hasColumn('users', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
