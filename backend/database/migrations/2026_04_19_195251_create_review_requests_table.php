<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('review_requests', function ($table) {
            $table->id();

            $table->foreignId('grade_id')->constrained()->onDelete('cascade');
            $table->foreignId('student_id')->constrained('users');

            $table->text('reason');

            $table->string('status')->default('pending');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
