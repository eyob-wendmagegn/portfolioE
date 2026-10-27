<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('technologies', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('slug')->nullable();
            $table->string('icon')->nullable();
            $table->timestamps();
        });

        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('short_description')->nullable();
            $table->longText('description')->nullable();
            $table->string('cover_image')->nullable();
            $table->foreignId('category_id')->nullable()->constrained('categories')->nullOnDelete();
            $table->string('live_url')->nullable();
            $table->string('github_url')->nullable();
            $table->boolean('featured')->default(false);
            $table->string('status')->default('published'); // published, draft
            $table->integer('sort_order')->default(0);
            $table->string('project_date')->nullable();
            $table->string('seo_title')->nullable();
            $table->text('seo_description')->nullable();
            $table->timestamps();
        });

        Schema::create('project_images', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained('projects')->cascadeOnDelete();
            $table->string('image_path');
            $table->string('caption')->nullable();
            $table->string('alt_text')->nullable();
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('project_technology', function (Blueprint $table) {
            $table->foreignId('project_id')->constrained('projects')->cascadeOnDelete();
            $table->foreignId('technology_id')->constrained('technologies')->cascadeOnDelete();
            $table->primary(['project_id', 'technology_id']);
            $table->timestamps();
        });

        Schema::create('skills', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('category'); // Backend Development, Frontend Development, Additional Technologies
            $table->string('icon')->nullable();
            $table->text('description')->nullable();
            $table->string('level')->nullable(); // e.g. Proficient, Advanced, Intermediate
            $table->integer('sort_order')->default(0);
            $table->boolean('is_visible')->default(true);
            $table->timestamps();
        });

        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description');
            $table->string('icon')->nullable();
            $table->string('action_text')->nullable();
            $table->string('action_url')->nullable();
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('education', function (Blueprint $table) {
            $table->id();
            $table->string('degree_or_title');
            $table->string('institution');
            $table->string('location')->nullable();
            $table->string('start_year')->nullable();
            $table->string('end_year')->nullable();
            $table->text('description')->nullable();
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('library_dashboard_images', function (Blueprint $table) {
            $table->id();
            $table->string('role')->unique(); // administrator, librarian, teacher, student
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('image_path')->nullable();
            $table->string('badge_color')->nullable();
            $table->string('alt_text')->nullable();
            $table->json('features')->nullable();
            $table->integer('sort_order')->default(0);
            $table->boolean('is_visible')->default(true);
            $table->timestamps();
        });

        Schema::create('contact_messages', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email');
            $table->string('subject');
            $table->string('project_type')->nullable();
            $table->text('message');
            $table->boolean('is_read')->default(false);
            $table->string('ip_address')->nullable();
            $table->timestamp('archived_at')->nullable();
            $table->timestamps();
        });

        Schema::create('site_settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->string('group')->default('general');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('site_settings');
        Schema::dropIfExists('contact_messages');
        Schema::dropIfExists('library_dashboard_images');
        Schema::dropIfExists('education');
        Schema::dropIfExists('services');
        Schema::dropIfExists('skills');
        Schema::dropIfExists('project_technology');
        Schema::dropIfExists('project_images');
        Schema::dropIfExists('projects');
        Schema::dropIfExists('technologies');
        Schema::dropIfExists('categories');
    }
};
