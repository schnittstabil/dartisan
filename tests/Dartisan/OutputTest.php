<?php

namespace Schnittstabil\Dartisan;

use PHPUnit\Framework\TestCase;

class OutputTest extends TestCase
{
    public function testFormatedTextShouldBeAltered()
    {
        $messages = [];
        $sut = new Output(function ($message) use (&$messages): string {
            $messages[] = $message;

            return (string) count($messages);
        });

        $this->setOutputCallback(function ($output) {
            $this->assertEquals($output, '12');
        });

        $sut->info('info', false);
        $sut->error('error', false);

        $this->assertEquals('<info>info</info>', $messages[0]);
        $this->assertEquals('<error>error</error>', $messages[1]);
    }
}
