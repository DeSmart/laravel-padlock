<?php

namespace DeSmart\Padlock\Entity;

use Carbon\Carbon;

class PadlockTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function it_has_proper_getters()
    {
        $name = 'Foo';
        $createdAt = new Carbon('2017-02-14 09:46:34', new \DateTimeZone('UTC'));

        $padlock = new Padlock($name, $createdAt);

        $this->assertEquals($name, $padlock->getName());
        $this->assertSame($createdAt, $padlock->getCreatedAt());
    }
}

