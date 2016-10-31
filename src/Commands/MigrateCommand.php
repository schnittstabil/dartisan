<?php

namespace Schnittstabil\Dartisan\Commands;

use Garden\Cli\Cli;
use Garden\Cli\Args;
use Illuminate\Database\Migrations\Migrator;

class MigrateCommand extends Command
{
    use DatabaseAwareCommandTrait;
    use MigrationAwareCommandTrait;

    public static $name = 'migrate';
    protected $migrator;
    protected $defaultPath;

    public function __construct(
        Args $args,
        callable $outputFormatter,
        Migrator $migrator,
        $defaultPath
    ) {
        parent::__construct($args, $outputFormatter);
        $this->migrator = $migrator;
        $this->defaultPath = $defaultPath;
    }

    public function run()
    {
        $path = $this->args->getOpt('path', $this->defaultPath);
        $this->migrator->run($path, [
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
            ->description('Run the database migrations.')
            ->opt('path', 'The path of migrations files to be executed.')
            ->opt('pretend', 'Dump the SQL queries that would be run.')
            ->opt('step', 'Force the migrations to be run so they can be rolled back individually.');
    }
}
