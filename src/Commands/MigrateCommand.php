<?php

namespace Schnittstabil\Dartisan\Commands;

use Garden\Cli\Cli;
use Garden\Cli\Args;
use Illuminate\Database\Migrations\Migrator;

class MigrateCommand
{
    use DatabaseAwareCommandTrait;
    use MigrationAwareCommandTrait;

    public static $name = 'migrate';
    protected $args;
    protected $migrator;
    protected $defaultPath;
    protected $outputFormatter;

    public function __construct(Args $args, Migrator $migrator, $defaultPath, callable $outputFormatter)
    {
        $this->args = $args;
        $this->migrator = $migrator;
        $this->defaultPath = $defaultPath;
        $this->outputFormatter = $outputFormatter;
    }

    public function __invoke()
    {
        $path = $this->args->getOpt('path', $this->defaultPath);
        $this->migrator->run($path, [
            'pretend' => $this->args->getOpt('pretend', null),
            'step' => $this->args->getOpt('step', null),
        ]);

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
            ->description('Run the database migrations.')
            ->opt('path', 'The path of migrations files to be executed.')
            ->opt('pretend', 'Dump the SQL queries that would be run.')
            ->opt('step', 'Force the migrations to be run so they can be rolled back individually.');
    }
}
