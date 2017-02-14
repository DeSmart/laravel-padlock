<?php

namespace DeSmart\Padlock\Console;

use DeSmart\Padlock\PadlockHandler;

class UnlockCommandTest extends \PHPUnit_Framework_TestCase
{
    private $handler;

    public function setUp()
    {
        $this->handler = $this->prophesize(PadlockHandler::class);
    }

    /**
     * @return UnlockCommand
     */
    protected function createCommand()
    {
        return new UnlockCommand($this->handler->reveal());
    }

    /**
     * @test
     */
    public function it_unlocks_padlock()
    {
        $scriptName = 'Foo';

        $this->handler->isLocked($scriptName)->willReturn(true);
        $this->handler->unlock($scriptName)->shouldBeCalled();

        $command = $this->createCommand();

        $command->setLaravel(app());
        $input = new \Symfony\Component\Console\Input\ArrayInput([
            'scriptName' => $scriptName
        ]);
        $output = new \Symfony\Component\Console\Output\BufferedOutput();

        ob_start();
        $command->run($input, $output);
        $result = ob_get_clean();

        $this->assertEquals("Padlock removed for script '{$scriptName}'\n", $result);
    }

    /**
     * @test
     */
    public function it_does_not_unlock_unlocked_padlocks()
    {
        $scriptName = 'Foo';

        $this->handler->isLocked($scriptName)->willReturn(false);
        $this->handler->unlock($scriptName)->shouldNotBeCalled();

        $command = $this->createCommand();

        $command->setLaravel(app());
        $input = new \Symfony\Component\Console\Input\ArrayInput([
            'scriptName' => $scriptName
        ]);
        $output = new \Symfony\Component\Console\Output\BufferedOutput();

        ob_start();
        $command->run($input, $output);
        $result = ob_get_clean();

        $this->assertEquals("There is no padlock on script '{$scriptName}'\n", $result);
    }
}

