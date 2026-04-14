<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('level_sidebar_access', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('levelid')->index();
            $table->string('menu_key', 100);
            $table->timestamps();

            $table->unique(['levelid', 'menu_key'], 'level_sidebar_access_unique');
        });

        if (Schema::hasTable('level')) {
            $levels = DB::table('level')->select('levelid')->get();
            $menuKeys = [
                'data-blok',
                'data-plot',
                'data-almarhum',
                'data-kontak-keluarga',
                'data-user',
                'activity-log',
                'restore-data',
                'hak-akses',
                'settings',
            ];

            $rows = [];
            foreach ($levels as $level) {
                foreach ($menuKeys as $menuKey) {
                    $rows[] = [
                        'levelid' => (int) $level->levelid,
                        'menu_key' => $menuKey,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
            }

            if (! empty($rows)) {
                DB::table('level_sidebar_access')->insert($rows);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('level_sidebar_access');
    }
};
