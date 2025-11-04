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
        Schema::create('applications', function (Blueprint $table) {
            $table->id('application_id'); // PK for 'applications' - Laravel convention uses 'id'
            $table->string('application_type', 255);
            $table->string('application_ref_id', 255); // Assumed to be non-PK, non-FK
            $table->dateTime('application_date');
            $table->string('reason', 255);
            $table->bigInteger('pass_validity_days');
            $table->string('status', 255);
            $table->text('security_notes')->nullable(); // nullable
            $table->dateTime('pass_issued_date')->nullable(); // nullable
            
            // Polymorphic relation manually defined:
            // Use string for the ID to accommodate both Student (int) and Guest (string) IDs.
            $table->string('applicant_id'); 
            $table->string('applicant_type'); 
            $table->index(['applicant_id', 'applicant_type']); // Index for performance

            // FK to Administrators table on 'admin_id'
            $table->string('processed_by_admin', 255)->nullable(); // nullable
            $table->foreign('processed_by_admin')
                  ->references('admin_id')->on('administrators')
                  ->onDelete('set null'); // Set to 'set null' since it's nullable
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('applications');
    }
};
