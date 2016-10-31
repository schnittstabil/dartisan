<?php

namespace Schnittstabil\Dartisan\Commands;

use Garden\Cli\Cli;
use Garden\Cli\Args;
use Illuminate\Database\Migrations\Migrator;

class MigrateRollbackCommand extends Command
{
    use DatabaseAwareCommandTrait;
    use MigrationAwareCommandTrait;

    public static $name = 'migrate:rollback';
    protected $migrator;
    protected $migrationsPath;

    public function __construct(
        Args $args,
        callable $outputFormatter,
        Migrator $migrator,
        $migrationsPath
    ) {
        parent::__construct($args, $outputFormatter);
        $this->migrator = $migrator;
        $this->migrationsPath = $migrationsPath;
    }

    public function run()
    {
        if (!$this->migrator->repositoryExists()) {
            $this->echoError('No migration table found.');

            return 1;
        }

        $path = $this->args->getOpt('path', $this->migrationsPath);
        $this->migrator->rollback($path, [
            'pretend' => $this->args->getOpt('pretend', null),
            'step' => $this->args->getOpt('step', null),
        ]);
        $this->echoNotes($this->migrator->getNotes());

        return 0;
    }

    public static function register(Cli $cli)
    {
        $cli = static::registerDatabaseOpts($cli);
        $cli = static::registerMigrationOpts($cli);

        return $cli
            ->command(static::$name)
            ->description('Rollback the last database migration.')
            ->opt('pretend', 'Dump the SQL queries that would be run.');
    }
}
