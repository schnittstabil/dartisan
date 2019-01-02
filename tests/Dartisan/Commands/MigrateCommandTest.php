<?php

namespace Schnittstabil\Dartisan\Commands;

use Illuminate\Database\Capsule\Manager as Capsule;
use PHPUnit\Framework\TestCase;
use Schnittstabil\Dartisan\Container;
use Schnittstabil\Dartisan\Output;
use Schnittstabil\Dartisan\OutputInterface;

/**
 * @SuppressWarnings(PHPMD.LongVariable)
 */
class MigrateCommandTest extends TestCase
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
        $container->set('migration-path', 'tests/fixtures/migrations/MigrateCommandTest');
        $container->set(OutputInterface::class, function () {
            return new Output();
        });

        $this->container = $container;
    }

    public function testMigrateShouldCreateTable()
    {
        $this->container->set('argv', ['-', 'migrate']);
        $sut = $this->container->get('command');
        $migrateInstallCommand = $this->container->get(MigrateInstallCommand::class);
        $migrateInstallCommand();
        $capsule = $this->container->get(Capsule::class);

        $this->setOutputCallback(function ($output) {
            $output = trim($output);
            $this->assertContains('<info>Migrated:</info>  0000_00_00_000000_MigrateCommandTestCreateUsers', $output);
        });

        $this->assertFalse($capsule->schema()->hasTable('users'));
        $this->assertSame(0, $sut());
        $this->assertTrue($capsule->schema()->hasTable('users'));

        $entries = $capsule->table('users')->get();
        $this->assertEquals(0, count($entries));
    }
}
