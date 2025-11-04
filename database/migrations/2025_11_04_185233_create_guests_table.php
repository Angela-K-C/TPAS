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
        Schema::create('guests', function (Blueprint $table) {
            $table->string('guest_id', 255)->primary(); // PK
            $table->string('first_name', 255);
            $table->string('last_name', 255);
            $table->string('email_address', 255)->unique(); // UK
            $table->string('mobile_number', 255)->unique(); // UK
            $table->string('identification_type', 255);
            $table->string('identification_number', 255)->unique(); // UK
            $table->text('visit_purpose');
            $table->string('guest_photo_url', 255)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('guests');
    }
};
