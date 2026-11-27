<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Database\Migrations\MigrationRepositoryInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class BaselineExistingMigrations extends Command
{
    protected $signature = 'migrate:baseline {--apply : Record migrations whose complete table sets already exist}';

    protected $description = 'Safely reconcile migration history for an existing database';

    private const MIGRATION_TABLES = [
        '2014_10_12_000000_create_users_table' => ['users'],
        '2014_10_12_100000_create_password_reset_tokens_table' => ['password_reset_tokens'],
        '2019_08_19_000000_create_failed_jobs_table' => ['failed_jobs'],
        '2019_12_14_000001_create_personal_access_tokens_table' => ['personal_access_tokens'],
        '2026_11_09_000001_create_profile_tables' => [
            'profiles',
            'social_links',
            'education',
            'documentaries',
            'gallery_items',
            'slides',
        ],
        '2026_11_13_000001_create_media_files_table' => ['media_files'],
        '2026_11_27_000001_create_request_activities_table' => ['request_activities'],
        '2026_11_27_000002_create_audit_logs_table' => ['audit_logs'],
        '2026_11_27_000004_create_security_benefit_tables' => [
            'security_benefit_modules',
            'security_benefit_categories',
            'security_benefits',
        ],
    ];

    public function handle(): int
    {
        /** @var MigrationRepositoryInterface $repository */
        $repository = $this->laravel->make('migration.repository');
        $recorded = $repository->repositoryExists() ? $repository->getRan() : [];
        $toBaseline = [];
        $pending = [];
        $problems = [];

        foreach (self::MIGRATION_TABLES as $migration => $tables) {
            $existing = array_values(array_filter($tables, fn (string $table) => Schema::hasTable($table)));
            $missing = array_values(array_diff($tables, $existing));

            if (in_array($migration, $recorded, true)) {
                if ($missing) {
                    $problems[] = "{$migration} is recorded as run, but is missing: ".implode(', ', $missing);
                }

                continue;
            }

            if (! $missing) {
                $toBaseline[] = $migration;
            } elseif (! $existing) {
                $pending[] = $migration;
            } else {
                $problems[] = "{$migration} is only partially present; existing: "
                    .implode(', ', $existing).'; missing: '.implode(', ', $missing);
            }
        }

        if ($problems) {
            foreach ($problems as $problem) {
                $this->components->error($problem);
            }

            $this->components->error('No migration records were changed. Reconcile these tables manually before migrating.');

            return self::FAILURE;
        }

        if ($toBaseline) {
            $this->components->info('Migrations whose complete table sets already exist:');
            foreach ($toBaseline as $migration) {
                $this->line("  - {$migration}");
            }
        } else {
            $this->components->info('No existing migrations need to be recorded.');
        }

        if ($pending) {
            $this->newLine();
            $this->components->info('Migrations with no existing tables will remain pending for php artisan migrate:');
            foreach ($pending as $migration) {
                $this->line("  - {$migration}");
            }
        }

        if (! $this->option('apply')) {
            $this->newLine();
            $this->components->warn('Dry run only. Confirm the existing schemas match these migrations, then rerun with --apply.');

            return self::SUCCESS;
        }

        if (! $toBaseline) {
            $this->components->info('No migration history changes were needed.');

            return self::SUCCESS;
        }

        if (! $repository->repositoryExists()) {
            $repository->createRepository();
        }

        $batch = $repository->getNextBatchNumber();
        DB::transaction(function () use ($repository, $toBaseline, $batch): void {
            foreach ($toBaseline as $migration) {
                $repository->log($migration, $batch);
            }
        });

        $this->components->info('Recorded existing migrations without changing application tables.');

        if ($pending) {
            $this->line('Run php artisan migrate to create the remaining tables.');
        }

        return self::SUCCESS;
    }
}
