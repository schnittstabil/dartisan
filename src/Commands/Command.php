<?php

namespace Schnittstabil\Dartisan\Commands;

use Garden\Cli\Args;

abstract class Command
{
    protected $args;
    protected $outputFormatter;

    public function __construct(Args $args, callable $outputFormatter)
    {
        $this->args = $args;
        $this->outputFormatter = $outputFormatter;
    }

    abstract protected function run();

    public function __invoke()
    {
        try {
            return $this->run();
        } catch (\Exception $e) {
            $this->echoError($e);

            return 2;
        }
    }

    /**
     * @SuppressWarnings(PHPMD.BooleanArgumentFlag)
     */
    protected function echoRaw($msg, $eol = true)
    {
        $outputFormatter = $this->outputFormatter;
        $output = $outputFormatter($msg);

        if ($eol) {
            $output .= PHP_EOL;
        }

        echo $output;
    }

    /**
     * @SuppressWarnings(PHPMD.BooleanArgumentFlag)
     */
    protected function echoError($msg, $eol = true)
    {
        $this->echoRaw('<error>'.$msg.'</error>', $eol);
    }

    /**
     * @SuppressWarnings(PHPMD.BooleanArgumentFlag)
     */
    protected function echoInfo($msg, $eol = true)
    {
        $this->echoRaw('<info>'.$msg.'</info>', $eol);
    }

    protected function echoNotes($notes)
    {
        foreach ($notes as $note) {
            $this->echoRaw($note);
        }
    }
}
