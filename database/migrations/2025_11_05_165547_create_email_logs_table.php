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
        Schema::create('email_logs', function (Blueprint $table) {
            $table->id();
            // Foreign key to the passes table. onDelete('cascade') ensures logs are deleted if the pass is deleted.
            $table->foreignId('temporary_pass_id')->constrained('temporary_passes')->onDelete('cascade');
            $table->string('recipient_email');
            $table->string('subject');
            $table->string('status');
            $table->text('error_message')->nullable();
            $table->timestamp('sent_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('email_logs');
    }
};