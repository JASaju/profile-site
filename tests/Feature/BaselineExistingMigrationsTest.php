<?php

namespace Tests\Feature;

use Illuminate\Database\Migrations\MigrationRepositoryInterface;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class BaselineExistingMigrationsTest extends TestCase
{
    public function test_dry_run_does_not_change_migration_history(): void
    {
        $existingTables = [
            'users',
            'profiles',
            'social_links',
            'education',
            'documentaries',
            'gallery_items',
            'slides',
            'media_files',
        ];

        Schema::shouldReceive('hasTable')
            ->andReturnUsing(fn (string $table) => in_array($table, $existingTables, true));

        $repository = \Mockery::mock(MigrationRepositoryInterface::class);
        $repository->shouldReceive('repositoryExists')->once()->andReturnFalse();
        $repository->shouldNotReceive('createRepository');
        $repository->shouldNotReceive('log');
        $this->app->instance('migration.repository', $repository);

        $this->artisan('migrate:baseline')
            ->expectsOutputToContain('Dry run only')
            ->assertExitCode(0);
    }

    public function test_partial_migration_schema_aborts_without_changing_history(): void
    {
        Schema::shouldReceive('hasTable')
            ->andReturnUsing(fn (string $table) => in_array($table, ['users', 'profiles'], true));

        $repository = \Mockery::mock(MigrationRepositoryInterface::class);
        $repository->shouldReceive('repositoryExists')->once()->andReturnFalse();
        $repository->shouldNotReceive('createRepository');
        $repository->shouldNotReceive('log');
        $this->app->instance('migration.repository', $repository);

        $this->artisan('migrate:baseline', ['--apply' => true])
            ->expectsOutputToContain('No migration records were changed')
            ->assertExitCode(1);
    }
}
