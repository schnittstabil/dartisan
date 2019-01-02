<?php

namespace Schnittstabil\Dartisan\Commands;

use Garden\Cli\Cli;

trait DatabaseAwareCommandTrait
{
    protected static function registerDatabaseDriver(Cli $cli)
    {
        return $cli->opt(
            'connection-driver',
            'The database driver to use, defaults to DB_DRIVER and "mysql"'
        )->opt(
            'connection-prefix',
            'The table prefix to use, defaults to DB_PREFIX and ""'
        )->opt(
            'connection-charset',
            'The character set to use, defaults to DB_CHARSET and "utf8"'
        );
    }

    protected static function registerDatabaseConnection(Cli $cli)
    {
        return $cli->opt(
            'connection-host',
            'The host to use, defaults to DB_HOST and "localhost"'
        )->opt(
            'connection-database',
            'The databse to use, defaults to DB_DATABASE and "forge"'
        );
    }

    protected static function registerDatabaseCredentials(Cli $cli)
    {
        return $cli->opt(
            'connection-username',
            'The username to use, defaults to DB_USERNAME and "forge"'
        )->opt(
            'connection-password',
            'The password to use, defaults to DB_PASSWORD and ""'
        );
    }

    protected static function registerDatabasePostgreSqlOpts(Cli $cli)
    {
        return $cli->opt(
            'connection-schema',
            'PostgreSQL only: The schema to use, defaults to DB_SCHEMA and "public"'
        );
    }

    protected static function registerDatabaseMySqlOpts(Cli $cli)
    {
        return $cli->opt(
            'connection-collation',
            'MySQL only: The collation to use, defaults to DB_COLLATION and "utf8_unicode_ci"'
        )->opt(
            'connection-strict',
            'MySQL only: Force strict mode, detaults to DB_STRICT and false',
            false,
            'boolean'
        );
    }

    /**
     * @param Cli $cli
     * @return Cli
     */
    public static function registerDatabaseOpts(Cli $cli)
    {
        $cli = static::registerDatabaseDriver($cli);
        $cli = static::registerDatabaseConnection($cli);
        $cli = static::registerDatabaseCredentials($cli);
        $cli = static::registerDatabasePostgreSqlOpts($cli);
        $cli = static::registerDatabaseMySqlOpts($cli);

        return $cli;
    }
}
