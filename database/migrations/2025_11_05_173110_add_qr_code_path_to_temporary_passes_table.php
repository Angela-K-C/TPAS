<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('temporary_passes', function (Blueprint $table) {
            if (!Schema::hasColumn('temporary_passes', 'qr_code_path')) {
                $table->string('qr_code_path')->nullable()->after('qr_code_token');
            }
        });
    }

    public function down(): void
    {
        Schema::table('temporary_passes', function (Blueprint $table) {
            if (Schema::hasColumn('temporary_passes', 'qr_code_path')) {
                $table->dropColumn('qr_code_path');
            }
        });
    }
};

