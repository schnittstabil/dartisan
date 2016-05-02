<?php

namespace Schnittstabil\Dartisan\ServiceProviders;

use Garden\Cli\Args;
use Illuminate\Database\Migrations\DatabaseMigrationRepository;
use Illuminate\Database\Migrations\MigrationCreator;
use Illuminate\Database\Migrations\Migrator;
use Schnittstabil\Dartisan\Commands\MigrateCommand;
use Schnittstabil\Dartisan\Commands\MigrateInstallCommand;
use Schnittstabil\Dartisan\Commands\MigrateMakeCommand;
use Schnittstabil\Dartisan\Commands\MigrateResetCommand;
use Schnittstabil\Dartisan\Commands\MigrateRollbackCommand;
use Schnittstabil\Dartisan\Commands\MigrateStatusCommand;
use Schnittstabil\Dartisan\Container;
use Schnittstabil\Dartisan\OutputFormatter;

/**
 * @SuppressWarnings(PHPMD.ShortVariable)
 */
class CommandsServiceProvider
{
    protected function registerCommand(Container $container, $class, callable $factory)
    {
        $container->set($class, $factory);

        return ['name' => $class::$name, 'class' => $class];
    }

    protected function registerCommands(Container $container)
    {
        $commands = [];

        $commands[] = $this->registerCommand($container, MigrateCommand::class, function (Container $c) {
            return new MigrateCommand(
                $c->get(Args::class),
                $c->get(Migrator::class),
                $c->get('migration-path'),
                $c->get(OutputFormatter::class)
            );
        });

        $commands[] = $this->registerCommand($container, MigrateInstallCommand::class, function (Container $c) {
            return new MigrateInstallCommand(
                $c->get(Args::class),
                $c->get(DatabaseMigrationRepository::class),
                $c->get(OutputFormatter::class)
            );
        });

        $commands[] = $this->registerCommand($container, MigrateMakeCommand::class, function (Container $c) {
            return new MigrateMakeCommand(
                $c->get(Args::class),
                $c->get(MigrationCreator::class),
                $c->get('migration-path'),
                $c->get(OutputFormatter::class)
            );
        });

        $commands[] = $this->registerCommand($container, MigrateResetCommand::class, function (Container $c) {
            return new MigrateResetCommand(
                $c->get(Args::class),
                $c->get(Migrator::class),
                $c->get(OutputFormatter::class)
            );
        });

        $commands[] = $this->registerCommand($container, MigrateRollbackCommand::class, function (Container $c) {
            return new MigrateRollbackCommand(
                $c->get(Args::class),
                $c->get(Migrator::class),
                $c->get(OutputFormatter::class)
            );
        });

        $commands[] = $this->registerCommand($container, MigrateStatusCommand::class, function (Container $c) {
            return new MigrateStatusCommand(
                $c->get(Args::class),
                $c->get(Migrator::class),
                $c->get('migration-path'),
                $c->get(OutputFormatter::class)
            );
        });

        return $commands;
    }

    public function __invoke(Container $container)
    {
        $registeredCommands = $this->registerCommands($container);

        $commands = array_reduce($registeredCommands, function ($acc, $command) {
            $acc[$command['name']] = $command['class'];

            return $acc;
        }, []);

        $container->set('commands', function () use ($commands) {
            return $commands;
        });

        $container->set('command', function (Container $c) {
            $cmdName = $c->get(Args::class)->getCommand();
            $cmdClass = $c->get('commands')[$cmdName];

            return $c->get($cmdClass);
        });
    }
}
