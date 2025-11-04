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
       Schema::create('administrators', function (Blueprint $table) {
            $table->string('admin_id', 255)->primary(); // PK
            $table->string('email_address', 255)->unique(); // UK
            $table->string('first_name', 255);
            $table->string('role', 255);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('administrators');
    }
};
