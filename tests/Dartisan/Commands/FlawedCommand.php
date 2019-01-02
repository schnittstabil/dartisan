<?php

namespace Schnittstabil\Dartisan\Commands;

use Garden\Cli\Args;
use Schnittstabil\Dartisan\OutputInterface;

class FlawedCommand extends Command
{
    public function __construct(Args $args, OutputInterface $output)
    {
        parent::__construct($args, $output);
    }

    public function run()
    {
        throw new \RuntimeException('Flawed');
    }
}
