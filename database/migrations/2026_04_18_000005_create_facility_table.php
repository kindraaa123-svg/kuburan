<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('facility')) {
            Schema::create('facility', function (Blueprint $table): void {
                $table->increments('facilityid');
                $table->string('facility_name', 120);
                $table->string('facility_key', 40)->unique();
                $table->string('icon_emoji', 16)->nullable();
                $table->timestamps();
            });
        } else {
            Schema::table('facility', function (Blueprint $table): void {
                if (! Schema::hasColumn('facility', 'name')) {
                    $table->string('name')->nullable();
                }
                if (! Schema::hasColumn('facility', 'picture')) {
                    $table->string('picture')->nullable();
                }
                if (! Schema::hasColumn('facility', 'facility_name')) {
                    $table->string('facility_name', 120)->nullable();
                }
                if (! Schema::hasColumn('facility', 'facility_key')) {
                    $table->string('facility_key', 40)->nullable();
                }
                if (! Schema::hasColumn('facility', 'icon_emoji')) {
                    $table->string('icon_emoji', 16)->nullable();
                }
                if (! Schema::hasColumn('facility', 'created_at')) {
                    $table->timestamp('created_at')->nullable();
                }
                if (! Schema::hasColumn('facility', 'updated_at')) {
                    $table->timestamp('updated_at')->nullable();
                }
            });

            if (Schema::hasColumn('facility', 'facility_key')) {
                try {
                    DB::statement('ALTER TABLE facility ADD UNIQUE facility_facility_key_unique (facility_key)');
                } catch (\Throwable $exception) {
                    // Unique index may already exist on legacy schemas.
                }
            }
        }

        $defaultFacilities = [
            ['facility_name' => 'Pohon', 'facility_key' => 'pohon', 'icon_emoji' => 'T'],
            ['facility_name' => 'Pintu Masuk', 'facility_key' => 'pintu_masuk', 'icon_emoji' => 'G'],
            ['facility_name' => 'Jalan', 'facility_key' => 'jalan', 'icon_emoji' => 'R'],
        ];

        $hasFacilityKey = Schema::hasColumn('facility', 'facility_key');
        $hasLegacyName = Schema::hasColumn('facility', 'name');
        $hasLegacyPicture = Schema::hasColumn('facility', 'picture');
        $hasCreatedAt = Schema::hasColumn('facility', 'created_at');
        $hasUpdatedAt = Schema::hasColumn('facility', 'updated_at');

        foreach ($defaultFacilities as $facility) {
            $existsQuery = DB::table('facility');
            if ($hasFacilityKey) {
                $existsQuery->where('facility_key', $facility['facility_key']);
            } else {
                $existsQuery->where('facility_name', $facility['facility_name']);
            }
            if ($hasLegacyName) {
                $existsQuery->orWhere('name', $facility['facility_name']);
            }

            if ($existsQuery->exists()) {
                continue;
            }

            $insertPayload = [
                'facility_name' => $facility['facility_name'],
                'icon_emoji' => $facility['icon_emoji'],
            ];
            if ($hasLegacyName) {
                $insertPayload['name'] = $facility['facility_name'];
            }
            if ($hasLegacyPicture) {
                $insertPayload['picture'] = '';
            }
            if ($hasFacilityKey) {
                $insertPayload['facility_key'] = $facility['facility_key'];
            }
            if ($hasCreatedAt) {
                $insertPayload['created_at'] = now();
            }
            if ($hasUpdatedAt) {
                $insertPayload['updated_at'] = now();
            }

            DB::table('facility')->insert($insertPayload);
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('facility');
    }
};
