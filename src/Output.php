<?php

namespace Schnittstabil\Dartisan;

class Output implements OutputInterface
{
    /**
     * @var callable
     */
    protected $outputFormatter;

    /**
     * @param callable $outputFormatter
     */
    public function __construct(callable $outputFormatter = null)
    {
        $this->outputFormatter = $outputFormatter;
    }

    /**
     * @SuppressWarnings(PHPMD.BooleanArgumentFlag)
     * @param $message
     * @param bool $eol
     */
    public function raw(string $message, bool $eol = true): void
    {
        if ($outputFormatter = $this->outputFormatter) {
            $message = $outputFormatter($message);
        }

        if ($eol) {
            $message .= PHP_EOL;
        }

        echo $message;
    }

    /**
     * @SuppressWarnings(PHPMD.BooleanArgumentFlag)
     * @param string $msg
     * @param bool $eol
     */
    public function error(string $msg, bool $eol = true): void
    {
        $this->raw("<error>$msg</error>", $eol);
    }

    /**
     * @SuppressWarnings(PHPMD.BooleanArgumentFlag)
     * @param string $msg
     * @param bool $eol
     */
    public function info(string $msg, bool $eol = true): void
    {
        $this->raw('<info>'.$msg.'</info>', $eol);
    }
}
