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
        Schema::create('students', function (Blueprint $table) {
            $table->id('student_id'); // PK for 'students' - Laravel convention uses 'id'
            $table->string('first_name', 255);
            $table->string('last_name', 255);
            $table->string('email_address', 255)->unique(); // UK
            $table->string('mobile_number', 255)->unique(); // UK
            $table->string('program_of_study', 255);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
