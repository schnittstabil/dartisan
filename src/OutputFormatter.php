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
        $color = $this->color;

        return $color($text)->colorize();
    }
}
