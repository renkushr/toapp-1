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

        if (!Schema::hasColumn('users', 'payout_paypal_phone')) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('payout_paypal_phone', 40)->nullable()->after('payout_paypal_email');
            });
        }

        if (!Schema::hasColumn('users', 'payout_paypal_contact')) {
            Schema::table('users', function (Blueprint $table) {
                $after = Schema::hasColumn('users', 'payout_paypal_phone') ? 'payout_paypal_phone' : 'payout_paypal_email';
                $table->string('payout_paypal_contact', 20)->nullable()->after($after);
            });
        }
    }

    public function down(): void
    {
        if (!Schema::hasTable('users')) {
            return;
        }

        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'payout_paypal_contact')) {
                $table->dropColumn('payout_paypal_contact');
            }
            if (Schema::hasColumn('users', 'payout_paypal_phone')) {
                $table->dropColumn('payout_paypal_phone');
            }
        });
    }
};
