<?php

namespace DeSmart\Padlock\Console;

use Carbon\Carbon;
use DeSmart\Padlock\Entity\Padlock;
use DeSmart\Padlock\PadlockHandler;

class ListCommandTest extends \PHPUnit_Framework_TestCase
{
    private $handler;

    public function setUp()
    {
        $this->handler = $this->prophesize(PadlockHandler::class);
    }

    /**
     * @return ListCommand
     */
    protected function createCommand()
    {
        return new ListCommand($this->handler->reveal());
    }

    /**
     * @test
     */
    public function it_lists_all_padlocks()
    {
        $padlocksCollection = collect([
            new Padlock('Foo', new Carbon('2017-02-01 00:00:00')),
            new Padlock('Bar', new Carbon('2017-02-01 03:30:00')),
            new Padlock('Baz', new Carbon('2017-02-10 11:22:33')),
        ]);

        $this->handler->getAll()->willReturn($padlocksCollection);

        $command = $this->createCommand();

        ob_start();
        $command->handle();
        $result = ob_get_clean();

        $expected =
'Foo: 2017-02-01 00:00:00
Bar: 2017-02-01 03:30:00
Baz: 2017-02-10 11:22:33
';

        $this->assertEquals($expected, $result);
    }

    /**
     * @test
     */
    public function it_does_not_fail_on_empty_set()
    {
        $padlocksCollection = collect([]);

        $this->handler->getAll()->willReturn($padlocksCollection);

        $command = $this->createCommand();

        ob_start();
        $command->handle();
        $result = ob_get_clean();

        $expected = '';

        $this->assertEquals($expected, $result);
    }
}

