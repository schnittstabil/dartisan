<?php

namespace Schnittstabil\Dartisan\Commands;

use Illuminate\Database\Capsule\Manager as Capsule;
use PHPUnit\Framework\TestCase;
use Schnittstabil\Dartisan\Container;
use Schnittstabil\Dartisan\Output;
use Schnittstabil\Dartisan\OutputInterface;

class MigrateInstallCommandTest extends TestCase
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
        $container->set(OutputInterface::class, function () {
            return new Output();
        });

        $this->container = $container;
    }

    public function testMakeShouldCreateTable()
    {
        $this->container->set('argv', ['-', 'migrate:install']);
        $sut = $this->container->get('command');
        $capsule = $this->container->get(Capsule::class);
        $migrationTable = $this->container->get('migration-table');

        $this->setOutputCallback(function ($output) {
            $output = trim($output);
            $this->assertContains('<info>Migration table created successfully.</info>', $output);
        });

        $this->assertFalse($capsule->schema()->hasTable($migrationTable));
        $this->assertSame(0, $sut());
        $this->assertTrue($capsule->schema()->hasTable($migrationTable));
        $entries = $capsule->table($migrationTable)->get();
        $this->assertEquals(0, count($entries));
    }

    public function testMakeShouldNotRecreateTable()
    {
        $this->container->set('argv', ['-', 'migrate:install']);
        $sut = $this->container->get('command');
        $capsule = $this->container->get(Capsule::class);
        $migrationTable = $this->container->get('migration-table');

        $this->setOutputCallback(function ($output) {
            $output = trim($output);
            $this->assertContains('<info>Migration table created successfully.</info>', $output);
            $this->assertContains('<info>Migration table already exists.</info>', $output);
        });

        $this->assertFalse($capsule->schema()->hasTable($migrationTable));
        $this->assertSame(0, $sut());
        $this->assertTrue($capsule->schema()->hasTable($migrationTable));
        $capsule->table($migrationTable)->insert(
            ['migration' => 'test', 'batch' => 0]
        );
        $this->assertSame(0, $sut());
        $this->assertTrue($capsule->schema()->hasTable($migrationTable));
        $entries = $capsule->table($migrationTable)->get();
        $this->assertEquals(1, count($entries));
        $this->assertEquals('test', $entries[0]->migration);
        $this->assertEquals(0, $entries[0]->batch);
    }
}
