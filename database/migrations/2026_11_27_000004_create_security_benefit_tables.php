<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('security_benefit_modules', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('name');
            $table->string('subtitle')->nullable();
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->boolean('is_manual')->default(true);
            $table->timestamps();
        });

        Schema::create('security_benefit_categories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('module_id')->constrained('security_benefit_modules')->cascadeOnDelete();
            $table->string('slug');
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('content_type', 30)->default('benefits');
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->boolean('is_manual')->default(true);
            $table->timestamps();

            $table->unique(['module_id', 'slug']);
        });

        Schema::create('security_benefits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained('security_benefit_categories')->cascadeOnDelete();
            $table->string('slug')->unique();
            $table->string('label')->nullable();
            $table->string('title');
            $table->text('description');
            $table->text('potential_value')->nullable();
            $table->json('context')->nullable();
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->boolean('is_manual')->default(true);
            $table->timestamps();

            $table->index(['category_id', 'sort_order']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('security_benefits');
        Schema::dropIfExists('security_benefit_categories');
        Schema::dropIfExists('security_benefit_modules');
    }
};
