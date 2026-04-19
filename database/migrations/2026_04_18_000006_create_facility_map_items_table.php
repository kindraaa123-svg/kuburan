<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('facility_map_items')) {
            return;
        }

        Schema::create('facility_map_items', function (Blueprint $table): void {
            $table->bigIncrements('facility_map_itemid');
            $table->integer('facility_id')->index();
            $table->integer('map_x');
            $table->integer('map_y');
            $table->boolean('is_fixed')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('facility_map_items');
    }
};
