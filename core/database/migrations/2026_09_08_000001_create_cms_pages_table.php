<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cms_pages', function (Blueprint $table) {
            $table->id();
            $table->string('slug', 80)->unique();
            $table->string('title', 180);
            $table->longText('body')->nullable();
            $table->boolean('is_published')->default(true);
            $table->timestamps();
        });

        DB::table('cms_pages')->insert([
            'slug' => 'about-us',
            'title' => 'About Us',
            'body' => '<p>Welcome to To-App. Tell your customers who you are, what you offer, and how you help families plan with confidence.</p><p>Edit this page anytime from the admin dashboard.</p>',
            'is_published' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('cms_pages');
    }
};
