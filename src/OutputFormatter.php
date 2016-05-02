<?php

namespace Schnittstabil\Dartisan;

use Colors\Color;

class OutputFormatter
{
    protected $color;

    public function __construct()
    {
        $this->color = new Color();
        $this->color->setUserStyles([
            'info' => 'green',
            'error' => 'red',
        ]);
    }

    public function __invoke($text)
    {
        return call_user_func($this->color, $text)->colorize();
    }
}
