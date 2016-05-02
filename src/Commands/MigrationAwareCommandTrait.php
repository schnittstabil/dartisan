<?php

namespace Schnittstabil\Dartisan\Commands;

use Garden\Cli\Cli;

trait MigrationAwareCommandTrait
{
    public static function registerMigrationOpts(Cli $cli)
    {
        return $cli
            ->opt(
                'migration-path',
                'The migration path to use, defaults to DB_MIGRATION_PATH and "database/migrations"'
            )
            ->opt(
                'migration-table',
                'The migration table to use, defaults to DB_MIGRATION_TABLE and "migrations"'
            );
    }
}
