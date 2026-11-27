<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('audit_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('ip_address', 45)->nullable()->index();
            $table->string('method', 10);
            $table->string('path', 2048);
            $table->string('action', 100)->index();
            $table->string('description', 500);
            $table->json('old_values')->nullable();
            $table->json('new_values')->nullable();
            $table->string('severity', 20)->default('info')->index();
            $table->timestamps();

            $table->index(['created_at', 'severity']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('audit_logs');
    }
};
