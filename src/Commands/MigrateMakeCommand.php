<?php

namespace Schnittstabil\Dartisan\Commands;

use Garden\Cli\Cli;
use Garden\Cli\Args;
use Illuminate\Database\Migrations\MigrationCreator;

class MigrateMakeCommand
{
    use DatabaseAwareCommandTrait;
    use MigrationAwareCommandTrait;

    public static $name = 'make:migration';
    protected $args;
    protected $migrationCreator;
    protected $migrationsPath;
    protected $outputFormatter;

    public function __construct(
        Args $args,
        MigrationCreator $migrationCreator,
        $migrationsPath,
        callable $outputFormatter
    ) {
        $this->args = $args;
        $this->migrationCreator = $migrationCreator;
        $this->migrationsPath = $migrationsPath;
        $this->outputFormatter = $outputFormatter;
    }

    public function __invoke()
    {
        $name = trim($this->args->getArg('name'));
        $path = $this->args->getOpt('path', $this->migrationsPath);
        $create = $this->args->getOpt('create');
        $table = $this->args->getOpt('table', $create);

        $file = pathinfo($this->migrationCreator->create($name, $path, $table, $create), PATHINFO_FILENAME);
        echo call_user_func($this->outputFormatter, "<info>Created Migration:</info> $file").PHP_EOL;

        return 0;
    }

    public static function register(Cli $cli)
    {
        $cli = static::registerDatabaseOpts($cli);
        $cli = static::registerMigrationOpts($cli);

        return $cli
            ->command(static::$name)
            ->description('Create a new migration file.')
            ->opt('create', 'The table to be created.')
            ->opt('table', 'The table to migrate.')
            ->opt('path', 'The location where the migration file should be created.')
            ->arg('name', 'The name of the migration.', true);
    }
}
