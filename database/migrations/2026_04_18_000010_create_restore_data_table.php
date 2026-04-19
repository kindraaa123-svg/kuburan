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
        Schema::create('restore_data', function (Blueprint $table) {
            $table->bigIncrements('restoreid');
            $table->string('entity_type', 40)->index();
            $table->string('entity_id', 60)->nullable();
            $table->string('entity_label', 190)->nullable();
            $table->longText('payload');
            $table->timestamp('deleted_at')->nullable()->index();
            $table->unsignedInteger('deleted_by_user_id')->nullable()->index();
            $table->string('deleted_by_name', 255)->nullable();
            $table->string('deleted_by_username', 255)->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->string('longitude', 50)->nullable();
            $table->string('latitude', 50)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('restore_data');
    }
};

