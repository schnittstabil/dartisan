<?php

namespace Schnittstabil\Dartisan\Commands;

use Garden\Cli\Cli;
use Garden\Cli\Args;
use Illuminate\Database\Migrations\MigrationCreator;
use Schnittstabil\Dartisan\OutputInterface;

class MigrateMakeCommand extends Command
{
    use DatabaseAwareCommandTrait;
    use MigrationAwareCommandTrait;

    public static $name = 'make:migration';

    /**
     * @var MigrationCreator
     */
    protected $migrationCreator;

    /**
     * @var string
     */
    protected $migrationsPath;

    public function __construct(
        Args $args,
        OutputInterface $output,
        MigrationCreator $migrationCreator,
        string $migrationsPath
    ) {
        parent::__construct($args, $output);
        $this->migrationCreator = $migrationCreator;
        $this->migrationsPath = $migrationsPath;
    }

    public function run()
    {
        $name = trim($this->args->getArg('name'));
        $path = $this->args->getOpt('path', $this->migrationsPath);
        $create = $this->args->getOpt('create');
        $table = $this->args->getOpt('table', $create);

        $file = pathinfo($this->migrationCreator->create($name, $path, $table, $create), PATHINFO_FILENAME);
        $this->output->info('Created Migration:', false);
        $this->output->raw(' ', false);
        $this->output->raw($file);

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
