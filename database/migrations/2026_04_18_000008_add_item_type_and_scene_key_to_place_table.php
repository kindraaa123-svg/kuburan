<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('place')) {
            return;
        }

        Schema::table('place', function (Blueprint $table): void {
            if (! Schema::hasColumn('place', 'item_type')) {
                $table->string('item_type', 20)->nullable()->after('facilityid');
            }
            if (! Schema::hasColumn('place', 'scene_object_key')) {
                $table->string('scene_object_key', 120)->nullable()->after('item_type');
            }
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('place')) {
            return;
        }

        Schema::table('place', function (Blueprint $table): void {
            if (Schema::hasColumn('place', 'scene_object_key')) {
                $table->dropColumn('scene_object_key');
            }
            if (Schema::hasColumn('place', 'item_type')) {
                $table->dropColumn('item_type');
            }
        });
    }
};

