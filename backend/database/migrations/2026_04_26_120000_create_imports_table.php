<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('imports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('type');
            $table->string('file_name');
            $table->string('status')->default('completed');
            $table->unsignedInteger('processed_rows')->default(0);
            $table->unsignedInteger('created_users')->default(0);
            $table->unsignedInteger('updated_users')->default(0);
            $table->unsignedInteger('duplicates_count')->default(0);
            $table->json('summary')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('imports');
    }
};
