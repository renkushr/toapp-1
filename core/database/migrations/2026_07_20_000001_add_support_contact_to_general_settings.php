<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('general_settings', 'support_phone')) {
            Schema::table('general_settings', function (Blueprint $table) {
                $table->string('support_phone', 60)->nullable()->after('email_from_name');
            });
        }

        if (!Schema::hasColumn('general_settings', 'support_email')) {
            Schema::table('general_settings', function (Blueprint $table) {
                $table->string('support_email', 120)->nullable()->after('support_phone');
            });
        }
    }

    public function down(): void
    {
        Schema::table('general_settings', function (Blueprint $table) {
            if (Schema::hasColumn('general_settings', 'support_email')) {
                $table->dropColumn('support_email');
            }
            if (Schema::hasColumn('general_settings', 'support_phone')) {
                $table->dropColumn('support_phone');
            }
        });
    }
};
