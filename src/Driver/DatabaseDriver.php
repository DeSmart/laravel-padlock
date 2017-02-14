<?php

namespace DeSmart\Padlock\Driver;

use DeSmart\Padlock\Entity\Padlock;
use DeSmart\Padlock\Repository\PadlocksRepository;

class DatabaseDriver implements PadlockDriverInterface
{
    /** @var PadlocksRepository */
    private $padlocksRepository;

    /**
     * DatabaseDriver constructor.
     * @param PadlocksRepository $padlocksRepository
     */
    public function __construct(PadlocksRepository $padlocksRepository)
    {
        $this->padlocksRepository = $padlocksRepository;
    }

    /**
     * @param Padlock $padlock
     */
    public function lock(Padlock $padlock)
    {
        $this->padlocksRepository->save($padlock);
    }

    /**
     * @param Padlock $padlock
     */
    public function unlock(Padlock $padlock)
    {
        $this->padlocksRepository->delete($padlock);
    }

    /**
     * @param string $name
     * @return Padlock|null
     */
    public function get(string $name)
    {
        return $this->padlocksRepository->get(new Padlock($name));
    }

    /**
     * @return Padlock[]|\Illuminate\Database\Eloquent\Collection
     */
    public function getAll()
    {
        return $this->padlocksRepository->getAll();
    }
}
