<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (! Schema::hasTable('blocks')) {
            return;
        }

        if (! Schema::hasColumn('blocks', 'max_plots')) {
            Schema::table('blocks', function (Blueprint $table) {
                $table->unsignedInteger('max_plots')->default(15)->after('map_color');
            });
        }
    }

    public function down(): void
    {
        if (! Schema::hasTable('blocks')) {
            return;
        }

        if (Schema::hasColumn('blocks', 'max_plots')) {
            Schema::table('blocks', function (Blueprint $table) {
                $table->dropColumn('max_plots');
            });
        }
    }
};

