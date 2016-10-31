<?php

namespace Schnittstabil\Dartisan\Commands;

use Garden\Cli\Cli;
use Garden\Cli\Args;
use Garden\Cli\Table;
use Illuminate\Database\Migrations\Migrator;

class MigrateStatusCommand extends Command
{
    use DatabaseAwareCommandTrait;
    use MigrationAwareCommandTrait;

    public static $name = 'migrate:status';
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
        $ran = $this->migrator->getRepository()->getRan();
        $migrationFiles = $this->migrator->getMigrationFiles($path);
        $this->echoMigrationTable($ran, $migrationFiles);

        return 0;
    }

    /**
     * @SuppressWarnings(PHPMD.ElseExpression)
     **/
    protected function echoMigrationTable($ran, $migrationFiles)
    {
        if (count($migrationFiles) === 0) {
            $this->echoInfo('No migration files found.');

            return;
        }

        $table = new Table();
        $table->row()->bold('Ran')->bold('Migration');

        foreach ($migrationFiles as $migration) {
            $migrationName = $this->migrator->getMigrationName($migration);
            $table->row();

            if (in_array($migrationName, $ran)) {
                $table->green('Y');
            } else {
                $table->red('N');
            }

            $table->cell($migrationName);
        }

        $table->write();
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
