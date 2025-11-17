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
        $schema = Schema::connection(config('database.default'));

        if (! $schema->hasTable('temporary_passes')) {
            return;
        }

        $schema->table('temporary_passes', function (Blueprint $table) use ($schema) {
            if (! $schema->hasColumn('temporary_passes', 'host_name')) {
                $table->string('host_name')->nullable()->after('reason');
            }

            if (! $schema->hasColumn('temporary_passes', 'host_department')) {
                $table->string('host_department')->nullable()->after('host_name');
            }

            if (! $schema->hasColumn('temporary_passes', 'purpose')) {
                $table->text('purpose')->nullable()->after('host_department');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $schema = Schema::connection(config('database.default'));

        if (! $schema->hasTable('temporary_passes')) {
            return;
        }

        $schema->table('temporary_passes', function (Blueprint $table) use ($schema) {
            if ($schema->hasColumn('temporary_passes', 'host_name')) {
                $table->dropColumn('host_name');
            }

            if ($schema->hasColumn('temporary_passes', 'host_department')) {
                $table->dropColumn('host_department');
            }

            if ($schema->hasColumn('temporary_passes', 'purpose')) {
                $table->dropColumn('purpose');
            }
        });
    }
};
