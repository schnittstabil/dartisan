<?php

namespace Schnittstabil\Dartisan\Commands;

use Garden\Cli\Args;
use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\Migrations\Migrator;
use PHPUnit\Framework\TestCase;
use Schnittstabil\Dartisan\Container;
use Schnittstabil\Dartisan\Output;
use Schnittstabil\Dartisan\OutputInterface;

/**
 * @SuppressWarnings(PHPMD.LongVariable)
 */
class MigrateResetCommandTest extends TestCase
{
    /**
     * @var Container
     */
    protected $container;

    protected function setUp()
    {
        array_map('unlink', glob('tests/temp/migrations/*'));

        $container = new Container();
        $container->set('connection-driver', 'sqlite');
        $container->set('connection-database', ':memory:');
        $container->set('migration-path', 'tests/fixtures/migrations/MigrateResetCommandTest');
        $container->set(OutputInterface::class, function () {
            return new Output();
        });

        $this->container = $container;
    }

    public function testMigrateResetShouldDropTables()
    {
        $this->container->set('argv', ['-', 'migrate:reset']);
        $sut = $this->container->get('command');

        $migrateInstallCommand = $this->container->get(MigrateInstallCommand::class);
        $migrateCommand = $this->container->get(MigrateCommand::class);

        $capsule = $this->container->get(Capsule::class);

        $this->setOutputCallback(function ($output) {
            $output = trim($output);
            $this->assertContains(
                '<info>Rolled back:</info>  0000_00_00_000000_MigrateResetCommandTestCreateUsers',
                $output
            );
            $this->assertContains(
                '<info>Rolled back:</info>  0000_00_00_000001_MigrateResetCommandTestCreateFlights',
                $output
            );
            $this->assertSame(2, substr_count($output, 'Rolled back'));
        });

        $migrateInstallCommand();
        $migrateCommand();
        $this->assertTrue($capsule->schema()->hasTable('users'));
        $this->assertTrue($capsule->schema()->hasTable('flights'));
        $this->assertSame(0, $sut());
        $this->assertFalse($capsule->schema()->hasTable('flights'));
        $this->assertFalse($capsule->schema()->hasTable('users'));
    }

    public function testMigrateResetShouldDropStepTables()
    {
        $this->container->set('argv', ['-', 'migrate:reset']);
        $sut = $this->container->get('command');

        $migrateInstallCommand = $this->container->get(MigrateInstallCommand::class);
        $migrateCommand = new MigrateCommand(
            new Args(MigrateCommand::$name, ['step' => true]),
            $this->container->get(OutputInterface::class),
            $this->container->get(Migrator::class),
            $this->container->get('migration-path')
        );

        $capsule = $this->container->get(Capsule::class);
        $migrationTable = $this->container->get('migration-table');

        $this->setOutputCallback(function ($output) {
            $output = trim($output);
            $this->assertContains(
                '<info>Rolled back:</info>  0000_00_00_000001_MigrateResetCommandTestCreateFlights',
                $output
            );
            $this->assertContains(
                '<info>Rolled back:</info>  0000_00_00_000000_MigrateResetCommandTestCreateUsers',
                $output
            );
            $this->assertSame(2, substr_count($output, 'Rolled back'));
        });

        $migrateInstallCommand();
        $migrateCommand();
        $this->assertTrue($capsule->schema()->hasTable('users'));
        $this->assertTrue($capsule->schema()->hasTable('flights'));
        $this->assertSame(0, $sut());
        $this->assertFalse($capsule->schema()->hasTable('flights'));
        $this->assertFalse($capsule->schema()->hasTable('users'));
        $this->assertTrue($capsule->schema()->hasTable($migrationTable));
    }

    public function testMigrateResetShouldComplainAboutMissingMigrationTable()
    {
        $this->container->set('argv', ['-', 'migrate:reset']);
        $sut = $this->container->get('command');
        $capsule = $this->container->get(Capsule::class);
        $migrationTable = $this->container->get('migration-table');

        $this->setOutputCallback(function ($output) {
            $output = trim($output);
            $this->assertContains('<error>No migration table found.</error>', $output);
            $this->assertSame(0, substr_count($output, 'Rolled back'));
        });

        $this->assertFalse($capsule->schema()->hasTable($migrationTable));
        $this->assertFalse($capsule->schema()->hasTable('users'));
        $this->assertFalse($capsule->schema()->hasTable('flights'));
        $this->assertNotSame(0, $sut());
        $this->assertFalse($capsule->schema()->hasTable('flights'));
        $this->assertFalse($capsule->schema()->hasTable('users'));
        $this->assertFalse($capsule->schema()->hasTable($migrationTable));
    }
}
