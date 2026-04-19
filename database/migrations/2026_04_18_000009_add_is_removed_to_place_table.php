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
            if (! Schema::hasColumn('place', 'is_removed')) {
                $table->boolean('is_removed')->default(false)->after('scene_object_key');
            }
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('place')) {
            return;
        }

        Schema::table('place', function (Blueprint $table): void {
            if (Schema::hasColumn('place', 'is_removed')) {
                $table->dropColumn('is_removed');
            }
        });
    }
};

