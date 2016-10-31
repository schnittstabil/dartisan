<?php

namespace Schnittstabil\Dartisan\ServiceProviders;

use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\Migrations\DatabaseMigrationRepository;
use Illuminate\Database\Migrations\MigrationCreator;
use Illuminate\Database\Migrations\Migrator;
use Illuminate\Filesystem\Filesystem;
use Schnittstabil\Dartisan\Container;

/**
 * @SuppressWarnings(PHPMD.ShortVariable)
 */
class DatabaseServiceProvider
{
    protected function setCapsule(Container $c)
    {
        $c->set(Capsule::class, function (Container $c) {
            $capsule = new Capsule();
            $capsule->addConnection($c->getNamespace('connection'));
            $capsule->bootEloquent();
            $capsule->setAsGlobal();

            return $capsule;
        });
    }

    protected function setDatabaseMigrationRepository(Container $c)
    {
        $c->set(DatabaseMigrationRepository::class, function (Container $c) {
            return new DatabaseMigrationRepository(
                $c->get(Capsule::class)->getDatabaseManager(),
                $c->get('migration-table')
            );
        });
    }

    protected function setMigrator(Container $c)
    {
        $c->set(Migrator::class, function (Container $c) {
            return new Migrator(
                $c->get(DatabaseMigrationRepository::class),
                $c->get(Capsule::class)->getDatabaseManager(),
                $c->get(Filesystem::class)
            );
        });
    }

    protected function setMigrationCreator(Container $c)
    {
        $c->set(MigrationCreator::class, function (Container $c) {
            return new MigrationCreator($c->get(Filesystem::class));
        });
    }

    public function __invoke(Container $c)
    {
        $this->setCapsule($c);
        $this->setDatabaseMigrationRepository($c);
        $this->setMigrator($c);
        $this->setMigrationCreator($c);
    }
}
