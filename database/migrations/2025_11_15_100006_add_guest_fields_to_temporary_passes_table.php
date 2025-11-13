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
        Schema::table('temporary_passes', function (Blueprint $table) {
            $table->string('visitor_name')->nullable();
            $table->string('national_id')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('host_name')->nullable();
            $table->string('host_department')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('temporary_passes', function (Blueprint $table) {
            $table->dropColumn(['visitor_name', 'national_id', 'email', 'phone', 'host_name', 'host_department']);
        });
    }
};
