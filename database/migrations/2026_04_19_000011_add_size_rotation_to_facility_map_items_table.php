<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('facility_map_items')) {
            return;
        }

        Schema::table('facility_map_items', function (Blueprint $table): void {
            if (! Schema::hasColumn('facility_map_items', 'map_width')) {
                $table->integer('map_width')->nullable()->after('map_y');
            }
            if (! Schema::hasColumn('facility_map_items', 'map_height')) {
                $table->integer('map_height')->nullable()->after('map_width');
            }
            if (! Schema::hasColumn('facility_map_items', 'map_rotation')) {
                $table->decimal('map_rotation', 8, 2)->nullable()->after('map_height');
            }
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('facility_map_items')) {
            return;
        }

        Schema::table('facility_map_items', function (Blueprint $table): void {
            if (Schema::hasColumn('facility_map_items', 'map_rotation')) {
                $table->dropColumn('map_rotation');
            }
            if (Schema::hasColumn('facility_map_items', 'map_height')) {
                $table->dropColumn('map_height');
            }
            if (Schema::hasColumn('facility_map_items', 'map_width')) {
                $table->dropColumn('map_width');
            }
        });
    }
};
