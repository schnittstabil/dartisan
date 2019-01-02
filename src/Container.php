<?php

namespace Schnittstabil\Dartisan;

use Schnittstabil\Dartisan\ServiceProviders\AuxiliariesServiceProvider;
use Schnittstabil\Dartisan\ServiceProviders\CliServiceProvider;
use Schnittstabil\Dartisan\ServiceProviders\ConfigServiceProvider;
use Schnittstabil\Dartisan\ServiceProviders\CommandsServiceProvider;
use Schnittstabil\Dartisan\ServiceProviders\DatabaseServiceProvider;

/**
 * @SuppressWarnings(PHPMD.Superglobals)
 */
class Container
{
    protected $factories = [];
    protected $values = [];

    public function __construct(array $argv = null)
    {
        $this->set('argv', $argv === null ? $GLOBALS['argv'] : $argv);

        foreach ([
            new AuxiliariesServiceProvider(),
            new CliServiceProvider(),
            new ConfigServiceProvider(),
            new CommandsServiceProvider(),
            new DatabaseServiceProvider(),
        ] as $serviceProvider) {
            $serviceProvider($this);
        }
    }

    public function set($key, $value)
    {
        if (is_callable($value)) {
            $this->factories[$key] = $value;
            unset($this->values[$key]);

            return;
        }

        unset($this->factories[$key]);
        $this->values[$key] = $value;
    }

    public function get($key)
    {
        if (!array_key_exists($key, $this->values)) {
            $this->values[$key] = $this->factories[$key]($this);
        }

        return $this->values[$key];
    }

    public function getNamespace($namespace)
    {
        $prefix = $namespace.'-';
        $prefixLen = strlen($prefix);
        $values = [];
        $keys = array_filter($this->keys(), function ($key) use ($prefix, $prefixLen) {
            return substr($key, 0, $prefixLen) === $prefix;
        });

        foreach ($keys as $key) {
            $values[substr($key, $prefixLen)] = $this->get($key);
        }

        return $values;
    }

    public function keys()
    {
        return array_unique(array_merge(array_keys($this->factories), array_keys($this->values)));
    }
}
