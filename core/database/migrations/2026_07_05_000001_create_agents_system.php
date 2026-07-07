<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('agents')) {
            Schema::create('agents', function (Blueprint $table) {
                $table->id();
                $table->string('name', 120);
                $table->string('email', 255)->unique();
                $table->string('username', 80)->unique();
                $table->string('license_no', 80)->unique();
                $table->string('password');
                $table->boolean('status')->default(true)->index();
                $table->rememberToken();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('agent_zip_codes')) {
            Schema::create('agent_zip_codes', function (Blueprint $table) {
                $table->id();
                $table->foreignId('agent_id')->constrained('agents')->cascadeOnDelete();
                $table->string('zip_code', 5)->index();
                $table->timestamps();

                $table->unique(['agent_id', 'zip_code']);
            });
        }

        if (!Schema::hasColumn('users', 'agent_id')) {
            Schema::table('users', function (Blueprint $table) {
                $table->unsignedBigInteger('agent_id')->nullable()->after('ref_by')->index();
            });
        }

        if (!Schema::hasColumn('users', 'agent_license_no')) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('agent_license_no', 80)->nullable()->after('agent_id');
            });
        }

        if (!Schema::hasTable('agent_conversations')) {
            Schema::create('agent_conversations', function (Blueprint $table) {
                $table->id();
                $table->foreignId('agent_id')->constrained('agents')->cascadeOnDelete();
                $table->unsignedBigInteger('user_id');
                $table->timestamp('last_message_at')->nullable();
                $table->timestamps();

                $table->unique(['agent_id', 'user_id']);
                $table->index('user_id');
            });
        }

        if (!Schema::hasTable('agent_conversation_messages')) {
            Schema::create('agent_conversation_messages', function (Blueprint $table) {
                $table->id();
                $table->foreignId('conversation_id')->constrained('agent_conversations')->cascadeOnDelete();
                $table->string('sender_type', 10);
                $table->unsignedBigInteger('sender_id');
                $table->text('message');
                $table->timestamp('read_at')->nullable();
                $table->timestamps();

                $table->index(['conversation_id', 'created_at']);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('agent_conversation_messages');
        Schema::dropIfExists('agent_conversations');

        if (Schema::hasColumn('users', 'agent_license_no')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('agent_license_no');
            });
        }

        if (Schema::hasColumn('users', 'agent_id')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('agent_id');
            });
        }

        Schema::dropIfExists('agent_zip_codes');
        Schema::dropIfExists('agents');
    }
};
