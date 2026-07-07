<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('agent_conversation_attachments')) {
            Schema::create('agent_conversation_attachments', function (Blueprint $table) {
                $table->id();
                $table->foreignId('message_id')->constrained('agent_conversation_messages')->cascadeOnDelete();
                $table->string('file', 255);
                $table->string('original_name', 255);
                $table->string('mime_type', 120)->nullable();
                $table->unsignedInteger('size')->default(0);
                $table->timestamps();

                $table->index('message_id');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('agent_conversation_attachments');
    }
};
