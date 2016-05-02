<?php

namespace Schnittstabil\Dartisan\Commands;

use Garden\Cli\Args;
use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\Migrations\Migrator;
use Schnittstabil\Dartisan\Container;
use Schnittstabil\Dartisan\OutputFormatter;

/**
 * @SuppressWarnings(PHPMD.LongVariable)
 */
class MigrateStatusCommandTest extends \PHPUnit_Framework_TestCase
{
    protected function setUp()
    {
        array_map('unlink', glob('tests/temp/migrations/*'));

        $container = new Container();
        $container->set('connection-driver', 'sqlite');
        $container->set('connection-database', ':memory:');
        $container->set('migration-path', 'tests/fixtures/migrations/MigrateStatusCommandTest');
        $container->set(OutputFormatter::class, function () {
            return function ($text) {
                return $text;
            };
        });

        $this->container = $container;
    }

    public function testMigrateStatusShouldOutputStatus()
    {
        $this->container->set('argv', ['-', 'migrate:status']);
        $sut = $this->container->get('command');
        $capsule = $this->container->get(Capsule::class);

        $migrateInstallCommand = $this->container->get(MigrateInstallCommand::class);
        $migrateCommand = new MigrateCommand(
            new Args(MigrateCommand::$name, ['step' => true]),
            $this->container->get(Migrator::class),
            $this->container->get('migration-path'),
            $this->container->get(OutputFormatter::class)
        );

        $rollbackCommand = new MigrateRollbackCommand(
            new Args(MigrateCommand::$name),
            $this->container->get(Migrator::class),
            $this->container->get(OutputFormatter::class)
        );

        $this->setOutputCallback(function ($output) {
            $output = preg_replace('/\x1b[^m]*m/', '', $output);

            preg_match_all('/^\s+[ynYN]\s+/m', $output, $yns);
            $yns = strtolower(implode('', array_map('trim', $yns[0])));

            $this->assertSame('nn'.'yy'.'yn'.'nn', $yns);
        });

        $migrateInstallCommand();
        $this->assertSame(0, $sut());

        $migrateCommand();
        $this->assertTrue($capsule->schema()->hasTable('users'));
        $this->assertTrue($capsule->schema()->hasTable('flights'));
        $this->assertSame(0, $sut());

        $rollbackCommand();
        $this->assertFalse($capsule->schema()->hasTable('flights'));
        $this->assertTrue($capsule->schema()->hasTable('users'));
        $this->assertSame(0, $sut());

        $rollbackCommand();
        $this->assertFalse($capsule->schema()->hasTable('flights'));
        $this->assertFalse($capsule->schema()->hasTable('users'));
        $this->assertSame(0, $sut());
    }

    public function testMigrateStatusShouldComplainAboutMissingMigrationTable()
    {
        $this->container->set('argv', ['-', 'migrate:status']);
        $sut = $this->container->get('command');
        $capsule = $this->container->get(Capsule::class);
        $migrationTable = $this->container->get('migration-table');

        $this->setOutputCallback(function ($output) {
            $output = trim($output);
            $this->assertSame('<error>No migration table found.</error>', $output);
        });

        $this->assertFalse($capsule->schema()->hasTable($migrationTable));
        $this->assertFalse($capsule->schema()->hasTable('users'));
        $this->assertFalse($capsule->schema()->hasTable('flights'));
        $this->assertNotSame(0, $sut());
        $this->assertFalse($capsule->schema()->hasTable('flights'));
        $this->assertFalse($capsule->schema()->hasTable('users'));
        $this->assertFalse($capsule->schema()->hasTable($migrationTable));
    }

    public function testMigrateStatusShouldComplainAboutMissingMigrations()
    {
        $this->container->set('migration-path', 'tests/fixtures/migrations/empty');
        $this->container->set('argv', ['-', 'migrate:status']);
        $sut = $this->container->get('command');
        $capsule = $this->container->get(Capsule::class);
        $migrateInstallCommand = $this->container->get(MigrateInstallCommand::class);
        $migrationTable = $this->container->get('migration-table');

        $this->setOutputCallback(function ($output) {
            $output = trim($output);
            $this->assertContains('<info>No migration files found</info>', $output);
        });

        $migrateInstallCommand();
        $this->assertTrue($capsule->schema()->hasTable($migrationTable));
        $this->assertFalse($capsule->schema()->hasTable('users'));
        $this->assertFalse($capsule->schema()->hasTable('flights'));
        $this->assertSame(0, $sut());
        $this->assertFalse($capsule->schema()->hasTable('flights'));
        $this->assertFalse($capsule->schema()->hasTable('users'));
        $this->assertTrue($capsule->schema()->hasTable($migrationTable));
    }
}
