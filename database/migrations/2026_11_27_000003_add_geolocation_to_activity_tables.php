<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    private const GEOLOCATION_COLUMNS = [
        'country',
        'region',
        'city',
        'latitude',
        'longitude',
    ];

    public function up(): void
    {
        foreach (['request_activities', 'audit_logs'] as $tableName) {
            if (! Schema::hasTable($tableName)) {
                continue;
            }

            $missingColumns = array_values(array_filter(
                self::GEOLOCATION_COLUMNS,
                fn (string $column) => ! Schema::hasColumn($tableName, $column)
            ));

            if (! $missingColumns) {
                continue;
            }

            Schema::table($tableName, function (Blueprint $table) use ($missingColumns): void {
                foreach ($missingColumns as $column) {
                    if ($column === 'latitude' || $column === 'longitude') {
                        $table->decimal($column, 10, 7)->nullable();
                    } else {
                        $table->string($column, 100)->nullable();
                    }
                }
            });
        }
    }

    public function down(): void
    {
        foreach (['request_activities', 'audit_logs'] as $tableName) {
            if (! Schema::hasTable($tableName)) {
                continue;
            }

            $existingColumns = array_values(array_filter(
                self::GEOLOCATION_COLUMNS,
                fn (string $column) => Schema::hasColumn($tableName, $column)
            ));

            if ($existingColumns) {
                Schema::table($tableName, function (Blueprint $table) use ($existingColumns): void {
                    $table->dropColumn($existingColumns);
                });
            }
        }
    }
};
