<?php

namespace Schnittstabil\Dartisan\Commands;

use Garden\Cli\Cli;
use Garden\Cli\Args;
use Illuminate\Database\Migrations\MigrationRepositoryInterface;

class MigrateInstallCommand
{
    use DatabaseAwareCommandTrait;
    use MigrationAwareCommandTrait;

    public static $name = 'migrate:install';
    protected $args;
    protected $repository;
    protected $outputFormatter;

    public function __construct(Args $args, MigrationRepositoryInterface $repository, callable $outputFormatter)
    {
        $this->args = $args;
        $this->repository = $repository;
        $this->outputFormatter = $outputFormatter;
    }

    public function __invoke()
    {
        if ($this->repository->repositoryExists()) {
            echo call_user_func($this->outputFormatter, '<info>Migration table already exists.</info>').PHP_EOL;

            return 0;
        }

        $this->repository->createRepository();
        echo call_user_func($this->outputFormatter, '<info>Migration table created successfully.</info>').PHP_EOL;

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
