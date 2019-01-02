<?php

namespace Schnittstabil\Dartisan\Commands;

use Garden\Cli\Args;
use Schnittstabil\Dartisan\OutputInterface;

abstract class Command
{
    /**
     * @var Args
     */
    protected $args;

    /**
     * @var callable
     */
    protected $output;

    public function __construct(Args $args, OutputInterface $output)
    {
        $this->args = $args;
        $this->output = $output;
    }

    abstract protected function run();

    public function __invoke()
    {
        try {
            return $this->run();
        } catch (\Exception $e) {
            $this->output->error($e);

            return 2;
        }
    }
}
