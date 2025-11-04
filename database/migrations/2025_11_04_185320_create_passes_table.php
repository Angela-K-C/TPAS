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
        Schema::create('passes', function (Blueprint $table) {
            $table->string('pass_code', 255)->primary(); // PK
            $table->string('verification_status', 255); 
            $table->dateTime('expiry_time'); 
            $table->boolean('is_active'); 
            $table->dateTime('check_in_time')->nullable(); // nullable

            // FK to Applications table on 'application_id'
            // We use foreignIdFor to ensure correct BigInt type if 'applications.id' is BigInt
            $table->foreignId('application_id')
                  ->constrained('applications', 'application_id')
                  ->onDelete('cascade'); // If application is deleted, the pass should be too
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('passes');
    }
};
