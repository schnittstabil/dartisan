<?php

namespace Schnittstabil\Dartisan\Commands;

use Garden\Cli\Cli;
use Garden\Cli\Args;
use Illuminate\Database\Migrations\MigrationRepositoryInterface;

class MigrateInstallCommand extends Command
{
    use DatabaseAwareCommandTrait;
    use MigrationAwareCommandTrait;

    public static $name = 'migrate:install';
    protected $repository;

    public function __construct(
        Args $args,
        callable $outputFormatter,
        MigrationRepositoryInterface $repository
    ) {
        parent::__construct($args, $outputFormatter);
        $this->repository = $repository;
    }

    public function run()
    {
        if ($this->repository->repositoryExists()) {
            $this->echoInfo('Migration table already exists.');

            return 0;
        }

        $this->repository->createRepository();
        $this->echoInfo('Migration table created successfully.');

        return 0;
    }

    public static function register(Cli $cli)
    {
        $cli = static::registerDatabaseOpts($cli);
        $cli = static::registerMigrationOpts($cli);

        return $cli
            ->command(static::$name)
            ->description('Create the migration repository.');
    }
}
