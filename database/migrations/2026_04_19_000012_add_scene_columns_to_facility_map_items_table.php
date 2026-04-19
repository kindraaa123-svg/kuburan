<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('facility_map_items')) {
            return;
        }

        Schema::table('facility_map_items', function (Blueprint $table): void {
            if (! Schema::hasColumn('facility_map_items', 'item_type')) {
                $table->string('item_type', 20)->nullable()->after('facility_id');
            }
            if (! Schema::hasColumn('facility_map_items', 'scene_object_key')) {
                $table->string('scene_object_key', 120)->nullable()->after('item_type');
            }
            if (! Schema::hasColumn('facility_map_items', 'is_removed')) {
                $table->boolean('is_removed')->default(false)->after('is_fixed');
            }
        });

        if (Schema::hasColumn('facility_map_items', 'item_type')) {
            DB::table('facility_map_items')
                ->whereNull('item_type')
                ->update(['item_type' => 'icon']);
        }

        if (Schema::hasColumn('facility_map_items', 'is_removed')) {
            DB::table('facility_map_items')
                ->whereNull('is_removed')
                ->update(['is_removed' => false]);
        }
    }

    public function down(): void
    {
        if (! Schema::hasTable('facility_map_items')) {
            return;
        }

        Schema::table('facility_map_items', function (Blueprint $table): void {
            if (Schema::hasColumn('facility_map_items', 'is_removed')) {
                $table->dropColumn('is_removed');
            }
            if (Schema::hasColumn('facility_map_items', 'scene_object_key')) {
                $table->dropColumn('scene_object_key');
            }
            if (Schema::hasColumn('facility_map_items', 'item_type')) {
                $table->dropColumn('item_type');
            }
        });
    }
};
