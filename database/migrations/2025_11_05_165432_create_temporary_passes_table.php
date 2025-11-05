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
        Schema::create('temporary_passes', function (Blueprint $table) {
            $table->id();
            // Polymorphic Columns: creates `passable_id` and `passable_type`
            $table->morphs('passable'); 
            
            $table->string('status')->default('pending');
            $table->text('reason');
            $table->string('qr_code_token')->unique()->nullable();
            
            $table->dateTime('valid_from')->nullable();
            $table->dateTime('valid_until')->nullable();

            // Foreign Key to Admins: Nullable, linked to the `admins` table
            $table->foreignId('approved_by')->nullable()->constrained('admins')->onDelete('set null');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('temporary_passes');
    }
};