<?php

namespace DeSmart\Padlock\Driver;

use DeSmart\Padlock\Entity\Padlock;
use Illuminate\Support\Collection;

interface PadlockDriverInterface
{
    /**
     * @param Padlock $padlock
     */
    public function lock(Padlock $padlock);

    /**
     * @param Padlock $padlock
     */
    public function unlock(Padlock $padlock);

    /**
     * @param string $name
     * @return Padlock|null
     */
    public function get(string $name);

    /**
     * @return Collection|Padlock[]
     */
    public function getAll();
}
