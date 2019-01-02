<?php

namespace Schnittstabil\Dartisan\Commands;

use Garden\Cli\Cli;
use Garden\Cli\Args;
use Illuminate\Database\Migrations\Migrator;
use Schnittstabil\Dartisan\OutputInterface;

class MigrateResetCommand extends Command
{
    use DatabaseAwareCommandTrait;
    use MigrationAwareCommandTrait;

    public static $name = 'migrate:reset';

    /**
     * @var Migrator
     */
    protected $migrator;

    /**
     * @var string
     */
    protected $migrationsPath;

    public function __construct(
        Args $args,
        OutputInterface $output,
        Migrator $migrator,
        string $migrationsPath
    ) {
        parent::__construct($args, $output);
        $this->migrator = $migrator;
        $this->migrationsPath = $migrationsPath;
    }

    public function run()
    {
        if (!$this->migrator->repositoryExists()) {
            $this->output->error('No migration table found.');

            return 1;
        }

        $path = $this->args->getOpt('path', $this->migrationsPath);
        $pretend = $this->args->getOpt('pretend', false);
        $this->migrator->reset([$path], $pretend);

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
