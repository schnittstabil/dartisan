<?php

namespace Schnittstabil\Dartisan\Commands;

use Illuminate\Database\Capsule\Manager as Capsule;
use Schnittstabil\Dartisan\Container;
use Schnittstabil\Dartisan\OutputFormatter;

class MigrateInstallCommandTest extends \PHPUnit_Framework_TestCase
{
    protected function setUp()
    {
        array_map('unlink', glob('tests/temp/migrations/*'));

        $container = new Container();
        $container->set('connection-driver', 'sqlite');
        $container->set('connection-database', ':memory:');
        $container->set(OutputFormatter::class, function () {
            return function ($text) {
                return $text;
            };
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
