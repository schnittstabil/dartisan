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

    public function __invoke(Container $container)
    {
        foreach ([
            ['option' => 'connection-driver', 'env' => 'DB_DRIVER', 'default' => 'mysql'],
            ['option' => 'connection-host', 'env' => 'DB_HOST', 'default' => 'localhost'],
            ['option' => 'connection-database', 'env' => 'DB_DATABASE', 'default' => 'forge'],
            ['option' => 'connection-username', 'env' => 'DB_USERNAME', 'default' => 'forge'],
            ['option' => 'connection-password', 'env' => 'DB_PASSWORD', 'default' => ''],
            ['option' => 'connection-schema', 'env' => 'DB_SCHEMA', 'default' => 'public'],
            ['option' => 'connection-prefix', 'env' => 'DB_PREFIX', 'default' => ''],
            ['option' => 'connection-charset', 'env' => 'DB_CHARSET', 'default' => 'utf8'],
            ['option' => 'connection-collation', 'env' => 'DB_COLLATION', 'default' => 'utf8_unicode_ci'],
            [
                'option' => 'connection-strict', 'env' => 'DB_STRICT', 'default' => false,
                'type' => FILTER_VALIDATE_BOOLEAN,
            ],
            ['option' => 'migration-path', 'env' => 'DB_MIGRATION_PATH', 'default' => 'database/migrations'],
            ['option' => 'migration-table', 'env' => 'DB_MIGRATION_TABLE', 'default' => 'migrations'],
        ] as $entry) {
            $container->set($entry['option'], function (Container $c) use ($entry) {
                $defaultValue = $this->env($entry['env'], $entry['default']);
                if (isset($entry['type'])) {
                    $defaultValue = filter_var($defaultValue, $entry['type']);
                }

                return $c->get(Args::class)->getOpt($entry['option'], $defaultValue);
            });
        }
    }
}
