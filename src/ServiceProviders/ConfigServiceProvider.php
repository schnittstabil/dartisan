<?php

namespace Schnittstabil\Dartisan\ServiceProviders;

use Garden\Cli\Args;
use Schnittstabil\Dartisan\Container;

/**
 * @SuppressWarnings(PHPMD.ShortVariable)
 */
class ConfigServiceProvider
{
    protected function env($key, $default = null)
    {
        $value = getenv($key);

        return $value === false ? $default : $value;
    }

    protected function setEntry(Container $container, $option, $env, $default = null, $type = null)
    {
        $container->set($option, function (Container $c) use ($option, $env, $default, $type) {
            $defaultValue = $this->env($env, $default);

            if ($type !== null) {
                $defaultValue = filter_var($defaultValue, $type);
            }

            return $c->get(Args::class)->getOpt($option, $defaultValue);
        });
    }

    protected function setDatabaseDriver(Container $c)
    {
        $this->setEntry($c, 'connection-driver', 'DB_DRIVER', 'mysql');
        $this->setEntry($c, 'connection-prefix', 'DB_PREFIX', '');
        $this->setEntry($c, 'connection-charset', 'DB_CHARSET', 'utf8');
    }

    protected function setDatabaseConnection(Container $c)
    {
        $this->setEntry($c, 'connection-host', 'DB_HOST', 'localhost');
        $this->setEntry($c, 'connection-database', 'DB_DATABASE', 'forge');
    }

    protected function setDatabaseCredentials(Container $c)
    {
        $this->setEntry($c, 'connection-username', 'DB_USERNAME', 'forge');
        $this->setEntry($c, 'connection-password', 'DB_PASSWORD');
    }

    protected function setDatabasePostgreSqlOpts(Container $c)
    {
        $this->setEntry($c, 'connection-schema', 'DB_SCHEMA', 'public');
    }

    protected function setDatabaseMySqlOpts(Container $c)
    {
        $this->setEntry($c, 'connection-collation', 'DB_COLLATION', 'utf8_unicode_ci');
        $this->setEntry($c, 'connection-strict', 'DB_STRICT', false, FILTER_VALIDATE_BOOLEAN);
    }

    protected function setDatabaseAbstractionOpts(Container $c)
    {
        $this->setEntry($c, 'migration-path', 'DB_MIGRATION_PATH', 'database/migrations');
        $this->setEntry($c, 'migration-table', 'DB_MIGRATION_TABLE', 'migrations');
    }

    public function __invoke(Container $c)
    {
        $this->setDatabaseDriver($c);
        $this->setDatabaseConnection($c);
        $this->setDatabaseCredentials($c);
        $this->setDatabasePostgreSqlOpts($c);
        $this->setDatabaseMySqlOpts($c);
        $this->setDatabaseAbstractionOpts($c);
    }
}
