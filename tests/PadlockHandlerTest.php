<?php

namespace DeSmart\Padlock;

use Carbon\Carbon;
use DeSmart\Padlock\Driver\PadlockDriverInterface;
use DeSmart\Padlock\Entity\Padlock;
use DeSmart\Padlock\Exception\PadlockExistsException;
use Prophecy\Argument;

class PadlockHandlerTest extends \PHPUnit_Framework_TestCase
{
    private $driver;

    public function setUp()
    {
        $this->driver = $this->prophesize(PadlockDriverInterface::class);
    }

    /**
     * @return PadlockHandler
     */
    public function makeHandler()
    {
        return new PadlockHandler($this->driver->reveal());
    }

    /**
     * @test
     */
    public function it_locks_script()
    {
        $scriptName = 'Foo';

        $this->driver->get($scriptName)->willReturn(null);
        $this->driver->lock(Argument::type(Padlock::class))->shouldBeCalled();

        $handler = $this->makeHandler();

        $handler->lock($scriptName);
    }

    /**
     * @test
     */
    public function it_throws_exception_if_lock_already_exists()
    {
        $scriptName = 'Foo';
        $existingLock = new Padlock($scriptName, $createdAt = Carbon::now()->subHour());

        $this->driver->get($scriptName)->willReturn($existingLock);
        $this->driver->lock(Argument::type(Padlock::class))->shouldNotBeCalled();

        $this->expectException(PadlockExistsException::class);

        $handler = $this->makeHandler();

        $handler->lock($scriptName);
    }

    /**
     * @test
     */
    public function it_returns_false_if_lock_does_not_exist()
    {
        $scriptName = 'Foo';

        $this->driver->get($scriptName)->willReturn(null);

        $handler = $this->makeHandler();

        $this->assertFalse($handler->isLocked($scriptName));
    }

    /**
     * @test
     */
    public function it_returns_true_if_lock_exists_and_there_is_no_ttl_specified_or_ttl_is_false()
    {
        $scriptName = 'Foo';
        $existingLock = new Padlock($scriptName, $createdAt = Carbon::now()->subYear());

        $this->driver->get($scriptName)->willReturn($existingLock);

        $handler = $this->makeHandler();

        $this->assertTrue($handler->isLocked($scriptName));
        $this->assertTrue($handler->isLocked($scriptName, 0));
        $this->assertTrue($handler->isLocked($scriptName, false));
    }

    /**
     * @test
     */
    public function it_returns_true_for_not_expired_locks()
    {
        $scriptName = 'Foo';
        $existingLock = new Padlock($scriptName, $createdAt = Carbon::now()->subHour());

        $this->driver->get($scriptName)->willReturn($existingLock);

        $handler = $this->makeHandler();

        $this->assertTrue($handler->isLocked($scriptName, 3600));
    }

    /**
     * @test
     */
    public function it_returns_false_for_expired_locks_and_unlocks_the_script()
    {
        $scriptName = 'Foo';
        $existingLock = new Padlock($scriptName, $createdAt = Carbon::now()->subHour());

        $this->driver->get($scriptName)->willReturn($existingLock);
        $this->driver->unlock(Argument::type(Padlock::class))->shouldBeCalled();

        $handler = $this->makeHandler();

        $this->assertFalse($handler->isLocked($scriptName, 3599));
    }

    /**
     * @test
     */
    public function it_throws_exception_for_negative_ttl()
    {
        $scriptName = 'Foo';
        $existingLock = new Padlock($scriptName, $createdAt = Carbon::now()->subHour());

        $this->driver->get($scriptName)->willReturn($existingLock);
        $this->driver->unlock(Argument::type(Padlock::class))->shouldNotBeCalled();

        $this->expectException(\InvalidArgumentException::class);

        $handler = $this->makeHandler();

        $this->assertFalse($handler->isLocked($scriptName, -3));
    }

    /**
     * @test
     */
    public function it_unlocks_script()
    {
        $scriptName = 'Foo';

        $this->driver->unlock(Argument::type(Padlock::class))->shouldBeCalled();

        $handler = $this->makeHandler();

        $handler->unlock($scriptName);
    }

    /**
     * @test
     */
    public function it_fetches_all_locks()
    {
        $collection = collect([new Padlock('Foo'), new Padlock('Bar')]);
        $this->driver->getAll()->shouldBeCalled()->willReturn($collection);

        $handler = $this->makeHandler();

        $result = $handler->getAll();

        $this->assertSame($collection, $result);
    }
}

