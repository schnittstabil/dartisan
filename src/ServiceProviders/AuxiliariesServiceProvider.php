<?php

namespace Schnittstabil\Dartisan\ServiceProviders;

use Illuminate\Filesystem\Filesystem;
use Schnittstabil\Dartisan\Container;
use Schnittstabil\Dartisan\OutputFormatter;

class AuxiliariesServiceProvider
{
    public function __invoke(Container $container)
    {
        $container->set(Filesystem::class, function () {
            return new Filesystem();
        });

        $container->set(OutputFormatter::class, function () {
            return new OutputFormatter();
        });
    }
}
