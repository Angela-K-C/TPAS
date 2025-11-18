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
            if (! Schema::hasColumn('temporary_passes', 'pass_type')) {
                $table->string('pass_type')->nullable()->after('reason');
            }

            if (! Schema::hasColumn('temporary_passes', 'details')) {
                $table->text('details')->nullable()->after('purpose');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('temporary_passes', function (Blueprint $table) {
            if (Schema::hasColumn('temporary_passes', 'pass_type')) {
                $table->dropColumn('pass_type');
            }

            if (Schema::hasColumn('temporary_passes', 'details')) {
                $table->dropColumn('details');
            }
        });
    }
};
