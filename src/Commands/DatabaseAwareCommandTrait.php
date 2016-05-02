<?php

namespace Schnittstabil\Dartisan\Commands;

use Garden\Cli\Cli;

trait DatabaseAwareCommandTrait
{
    public static function registerDatabaseOpts(Cli $cli)
    {
        return $cli
            ->opt(
                'connection-driver',
                'The database driver to use, defaults to DB_DRIVER and "mysql"'
            )
            ->opt(
                'connection-host',
                'The host to use, defaults to DB_HOST and "localhost"'
            )
            ->opt(
                'connection-database',
                'The databse to use, defaults to DB_DATABASE and "forge"'
            )
            ->opt(
                'connection-username',
                'The username to use, defaults to DB_USERNAME and "forge"'
            )
            ->opt(
                'connection-password',
                'The password to use, defaults to DB_PASSWORD and ""'
            )
            ->opt(
                'connection-prefix',
                'The table prefix to use, defaults to DB_PREFIX and ""'
            )
            ->opt(
                'connection-charset',
                'The character set to use, defaults to DB_CHARSET and "utf8"'
            )
            ->opt(
                'connection-schema',
                'PostgreSQL only: The schema to use, defaults to DB_SCHEMA and "public"'
            )
            ->opt(
                'connection-collation',
                'MySQL only: The collation to use, defaults to DB_COLLATION and "utf8_unicode_ci"'
            )
            ->opt(
                'connection-strict',
                'MySQL only: Force strict mode, detaults to DB_STRICT and false',
                false,
                'boolean'
            );
    }
}
