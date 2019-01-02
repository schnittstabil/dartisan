<?php

namespace Schnittstabil\Dartisan\ServiceProviders;

use Illuminate\Filesystem\Filesystem;
use Schnittstabil\Dartisan\Container;
use Schnittstabil\Dartisan\Output;
use Schnittstabil\Dartisan\OutputFormatter;
use Schnittstabil\Dartisan\OutputInterface;

class AuxiliariesServiceProvider
{
    public function __invoke(Container $container)
    {
        $container->set(Filesystem::class, function () {
            return new Filesystem();
        });

        $container->set(OutputInterface::class, function () {
            return new Output(new OutputFormatter());
        });
    }
}
