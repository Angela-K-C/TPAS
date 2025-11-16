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
        Schema::connection('university')->table('temporary_passes', function (Blueprint $table) {
            if (!Schema::connection('university')->hasColumn('temporary_passes', 'host_name')) {
                $table->string('host_name')->nullable()->after('reason');
            }

            if (!Schema::connection('university')->hasColumn('temporary_passes', 'host_department')) {
                $table->string('host_department')->nullable()->after('host_name');
            }

            if (!Schema::connection('university')->hasColumn('temporary_passes', 'purpose')) {
                $table->text('purpose')->nullable()->after('host_department');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('university')->table('temporary_passes', function (Blueprint $table) {
            if (Schema::connection('university')->hasColumn('temporary_passes', 'host_name')) {
                $table->dropColumn('host_name');
            }

            if (Schema::connection('university')->hasColumn('temporary_passes', 'host_department')) {
                $table->dropColumn('host_department');
            }

            if (Schema::connection('university')->hasColumn('temporary_passes', 'purpose')) {
                $table->dropColumn('purpose');
            }
        });
    }
};