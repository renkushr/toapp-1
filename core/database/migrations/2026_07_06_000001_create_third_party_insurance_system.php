<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('third_party_portals')) {
            Schema::create('third_party_portals', function (Blueprint $table) {
                $table->id();
                $table->string('name', 120);
                $table->string('slug', 80)->unique();
                $table->string('base_path', 120)->default('/check-insure');
                $table->boolean('status')->default(true);
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('plan_third_party_portal')) {
            Schema::create('plan_third_party_portal', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('plan_id');
                $table->foreignId('third_party_portal_id')->constrained('third_party_portals')->cascadeOnDelete();
                $table->boolean('published')->default(true);
                $table->timestamps();

                $table->unique(['plan_id', 'third_party_portal_id']);
            });
        }

        if (!Schema::hasColumn('plans', 'allows_chronic')) {
            Schema::table('plans', function (Blueprint $table) {
                $table->boolean('allows_chronic')->default(true)->after('health');
            });
        }

        if (!Schema::hasColumn('users', 'third_party_portal_id')) {
            Schema::table('users', function (Blueprint $table) {
                $table->unsignedBigInteger('third_party_portal_id')->nullable()->after('agent_license_no')->index();
                $table->string('registration_channel', 20)->default('toapp')->after('third_party_portal_id');
                $table->boolean('chronic_disease')->default(false)->after('health');
            });
        }

        if (!Schema::hasTable('insurance_applications')) {
            Schema::create('insurance_applications', function (Blueprint $table) {
                $table->id();
                $table->foreignId('third_party_portal_id')->constrained('third_party_portals');
                $table->unsignedBigInteger('user_id')->index();
                $table->unsignedBigInteger('plan_id')->nullable()->index();
                $table->unsignedBigInteger('agent_id')->nullable()->index();
                $table->string('reference', 32)->unique();

                $table->string('status', 30)->default('otp_pending')->index();

                $table->unsignedTinyInteger('sex')->nullable();
                $table->date('birthday')->nullable();
                $table->string('zipcode', 10)->nullable();
                $table->boolean('chronic_disease')->default(false);
                $table->unsignedTinyInteger('smoking')->default(0);
                $table->unsignedTinyInteger('health')->default(1);
                $table->string('pay_type', 10)->nullable();
                $table->unsignedTinyInteger('price_range')->nullable();

                $table->decimal('quoted_monthly', 12, 2)->nullable();
                $table->decimal('quoted_annual', 12, 2)->nullable();

                $table->timestamp('terms_accepted_at')->nullable();
                $table->timestamp('chat_started_at')->nullable();
                $table->timestamp('payment_confirmed_at')->nullable();
                $table->unsignedBigInteger('confirmed_by_agent_id')->nullable();
                $table->unsignedBigInteger('confirmed_by_admin_id')->nullable();
                $table->text('admin_notes')->nullable();
                $table->text('rejection_reason')->nullable();

                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('insurance_applications');
        Schema::dropIfExists('plan_third_party_portal');
        Schema::dropIfExists('third_party_portals');

        if (Schema::hasColumn('users', 'chronic_disease')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn(['third_party_portal_id', 'registration_channel', 'chronic_disease']);
            });
        }

        if (Schema::hasColumn('plans', 'allows_chronic')) {
            Schema::table('plans', function (Blueprint $table) {
                $table->dropColumn('allows_chronic');
            });
        }
    }
};
