<?php

namespace Schnittstabil\Dartisan\Commands;

use Garden\Cli\Cli;
use Garden\Cli\Args;
use Illuminate\Database\Migrations\MigrationRepositoryInterface;
use Schnittstabil\Dartisan\OutputInterface;

class MigrateInstallCommand extends Command
{
    use DatabaseAwareCommandTrait;
    use MigrationAwareCommandTrait;

    public static $name = 'migrate:install';
    protected $repository;

    public function __construct(
        Args $args,
        OutputInterface $output,
        MigrationRepositoryInterface $repository
    ) {
        parent::__construct($args, $output);
        $this->repository = $repository;
    }

    public function run()
    {
        if ($this->repository->repositoryExists()) {
            $this->output->info('Migration table already exists.');

            return 0;
        }

        $this->repository->createRepository();
        $this->output->info('Migration table created successfully.');

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
