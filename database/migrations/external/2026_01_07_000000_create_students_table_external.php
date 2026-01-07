<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::connection('external_university')->create('students', function (Blueprint $table) {
            $table->integer('id')->primary();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->string('photo_url')->nullable();
        });
    }

    public function down(): void
    {
        Schema::connection('external_university')->dropIfExists('students');
    }
};