<?php

namespace Schnittstabil\Dartisan\Commands;

use Garden\Cli\Args;

class FlawedCommand extends Command
{
    public function __construct(Args $args, callable $outputFormatter)
    {
        parent::__construct($args, $outputFormatter);
    }

    public function run()
    {
        throw new \RuntimeException('Flawed');
    }
}
