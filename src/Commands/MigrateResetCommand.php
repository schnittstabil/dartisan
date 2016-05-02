<?php

namespace Schnittstabil\Dartisan\Commands;

use Garden\Cli\Cli;
use Garden\Cli\Args;
use Illuminate\Database\Migrations\Migrator;

class MigrateResetCommand
{
    use DatabaseAwareCommandTrait;
    use MigrationAwareCommandTrait;

    public static $name = 'migrate:reset';
    protected $args;
    protected $migrator;
    protected $outputFormatter;

    public function __construct(Args $args, Migrator $migrator, callable $outputFormatter)
    {
        $this->args = $args;
        $this->migrator = $migrator;
        $this->outputFormatter = $outputFormatter;
    }

    public function __invoke()
    {
        if (!$this->migrator->repositoryExists()) {
            echo call_user_func($this->outputFormatter, '<error>No migration table found.</error>').PHP_EOL;

            return 1;
        }

        $pretend = $this->args->getOpt('pretend');
        $this->migrator->reset($pretend);

        foreach ($this->migrator->getNotes() as $note) {
            echo call_user_func($this->outputFormatter, $note).PHP_EOL;
        }

        return 0;
    }

    public static function register(Cli $cli)
    {
        $cli = static::registerDatabaseOpts($cli);
        $cli = static::registerMigrationOpts($cli);

        return $cli
            ->command(static::$name)
            ->description('Rollback all database migrations.')
            ->opt('pretend', 'Dump the SQL queries that would be run.');
    }
}
