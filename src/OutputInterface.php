<?php

namespace Schnittstabil\Dartisan;

interface OutputInterface
{
    /**
     * @SuppressWarnings(PHPMD.BooleanArgumentFlag)
     * @param $message
     * @param bool $eol
     */
    public function raw(string $message, bool $eol = true);

    /**
     * @SuppressWarnings(PHPMD.BooleanArgumentFlag)
     * @param string $msg
     * @param bool $eol
     */
    public function error(string $msg, bool $eol = true);

    /**
     * @SuppressWarnings(PHPMD.BooleanArgumentFlag)
     * @param string $msg
     * @param bool $eol
     */
    public function info(string $msg, bool $eol = true);
}
