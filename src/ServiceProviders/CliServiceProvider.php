<?php

namespace Schnittstabil\Dartisan\ServiceProviders;

use Garden\Cli\Cli;
use Garden\Cli\Args;
use Schnittstabil\Dartisan\Container;

/**
 * @SuppressWarnings(PHPMD.ShortVariable)
 */
class CliServiceProvider
{
    public function __invoke(Container $container)
    {
        $container->set(Cli::class, function (Container $c) {
            $cli = new Cli();

            foreach ($c->get('commands') as $class) {
                $class::register($cli);
            }

            return $cli;
        });

        $container->set(Args::class, function (Container $c) {
            return $c->get(Cli::class)->parse($c->get('argv'), true);
        });
    }
}
