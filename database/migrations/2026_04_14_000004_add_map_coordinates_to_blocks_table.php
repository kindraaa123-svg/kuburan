<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('blocks', function (Blueprint $table): void {
            if (! Schema::hasColumn('blocks', 'map_x')) {
                $table->integer('map_x')->nullable()->after('map_color');
            }

            if (! Schema::hasColumn('blocks', 'map_y')) {
                $table->integer('map_y')->nullable()->after('map_x');
            }
        });
    }

    public function down(): void
    {
        Schema::table('blocks', function (Blueprint $table): void {
            if (Schema::hasColumn('blocks', 'map_y')) {
                $table->dropColumn('map_y');
            }

            if (Schema::hasColumn('blocks', 'map_x')) {
                $table->dropColumn('map_x');
            }
        });
    }
};
