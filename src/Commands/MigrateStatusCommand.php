<?php

namespace Schnittstabil\Dartisan\Commands;

use Garden\Cli\Cli;
use Garden\Cli\Args;
use Garden\Cli\Table;
use Illuminate\Database\Migrations\Migrator;

class MigrateStatusCommand
{
    use DatabaseAwareCommandTrait;
    use MigrationAwareCommandTrait;

    public static $name = 'migrate:status';
    protected $args;
    protected $migrator;
    protected $migrationsPath;
    protected $outputFormatter;

    public function __construct(Args $args, Migrator $migrator, $migrationsPath, callable $outputFormatter)
    {
        $this->args = $args;
        $this->migrator = $migrator;
        $this->migrationsPath = $migrationsPath;
        $this->outputFormatter = $outputFormatter;
    }

    public function __invoke()
    {
        if (!$this->migrator->repositoryExists()) {
            echo call_user_func($this->outputFormatter, '<error>No migration table found.</error>').PHP_EOL;

            return 1;
        }

        $path = $this->args->getOpt('path', $this->migrationsPath);
        $ran = $this->migrator->getRepository()->getRan();
        $migrationFiles = $this->migrator->getMigrationFiles($path);

        if (count($migrationFiles) === 0) {
            echo call_user_func($this->outputFormatter, '<info>No migration files found</info> ').PHP_EOL;

            return 0;
        }

        $table = new Table();
        $table
            ->row()
            ->bold('Ran')
            ->bold('Migration');

        foreach ($migrationFiles as $migration) {
            $table->row();
            in_array($migration, $ran) ? $table->green('Y') : $table->red('N');
            $table->cell($migration);
        }

        $table->write();

        return 0;
    }

    public static function register(Cli $cli)
    {
        $cli = static::registerDatabaseOpts($cli);
        $cli = static::registerMigrationOpts($cli);

        return $cli
            ->command(static::$name)
            ->description('Show the status of each migration.')
            ->opt('path', 'The path of migrations files to be executed.');
    }
}
