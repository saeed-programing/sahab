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
        Schema::create('student_phones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('students')->cascadeOnDelete();
            $table->enum('phone_for', ['Father', 'Mother', 'Student', 'Other'])->index();
            $table->string('phone_num')->index();
            $table->string('description')->nullable();
            $table->boolean('is_just_virtual')->default(0);
            $table->timestamps();

            $table->index(['is_just_virtual', 'phone_for']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_phones');
    }
};
