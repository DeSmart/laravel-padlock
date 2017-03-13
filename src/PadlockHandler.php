<?php

namespace DeSmart\Padlock;

use Carbon\Carbon;
use DeSmart\Padlock\Driver\PadlockDriverInterface;
use DeSmart\Padlock\Entity\Padlock;
use DeSmart\Padlock\Exception\PadlockExistsException;

class PadlockHandler
{
    /** @var PadlockDriverInterface */
    private $driver;

    private $enabled;

    /**
     * PadlockHandler constructor.
     * @param PadlockDriverInterface $driver
     */
    public function __construct(PadlockDriverInterface $driver, bool $enabled)
    {
        $this->driver = $driver;
        $this->enabled = (bool)$enabled;
    }

    /**
     * @param string $name
     */
    public function lock(string $name)
    {
        if (false === $this->isEnabled()) {
            return;
        }

        if (true === $this->isLocked($name)) {
            throw new PadlockExistsException("Padlock already locked for script '{$name}'");
        }

        $this->driver->lock(new Padlock($name));
    }

    /**
     * @param string $name
     * @param int|null $ttl Lock time limit, in seconds
     * @return bool
     */
    public function isLocked(string $name, int $ttl = null)
    {
        if (false === $this->isEnabled()) {
            return false;
        }

        $padlock = $this->driver->get($name);

        if (null === $padlock) {
            return false;
        }

        if (true === empty($ttl)) {
            return true;
        }

        if ($ttl < 0) {
            throw new \InvalidArgumentException("Padlock TTL cannot be negative!");
        }

        $now = Carbon::now(new \DateTimeZone('UTC'));

        if (($padlock->getCreatedAt()->timestamp + $ttl) < $now->timestamp) {
            $this->unlock($name);

            return false;
        }

        return true;
    }

    /**
     * @param string $name
     */
    public function unlock(string $name)
    {
        if (false === $this->isEnabled()) {
            return;
        }

        $this->driver->unlock(new Padlock($name));
    }

    /**
     * @return Padlock[]|\Illuminate\Support\Collection
     */
    public function getAll()
    {
        return $this->driver->getAll();
    }

    /**
     * @return bool
     */
    private function isEnabled()
    {
        return $this->enabled;
    }
}
