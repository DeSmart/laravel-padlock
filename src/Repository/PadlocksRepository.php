<?php

namespace DeSmart\Padlock\Repository;

use DeSmart\Padlock\Entity\Padlock;
use DeSmart\Padlock\Model\PadlockModel;
use Illuminate\Database\Eloquent\Collection;

class PadlocksRepository
{
    /** @var PadlockModel */
    private $query;

    /**
     * PadlocksRepository constructor.
     * @param PadlockModel $query
     */
    public function __construct(PadlockModel $query)
    {
        $this->query = $query;
    }

    /**
     * @param Padlock $padlock
     * @return Padlock
     */
    public function save(Padlock $padlock)
    {
        $model = $this->query->createFromEntity($padlock);
        
        $model->save();
        
        return $padlock;
    }

    /**
     * @param Padlock $padlock
     * @return Padlock|null
     */
    public function get(Padlock $padlock)
    {
        /** @var PadlockModel $model */
        $model = $this->query->newQuery()->where('name', $padlock->getName())->first();

        if (null === $model) {
            return null;
        }

        return $model->toEntity();
    }

    /**
     * @return Collection|Padlock[]
     */
    public function getAll()
    {
        return $this->query->get()->map(function (PadlockModel $model) {
            return $model->toEntity();
        });
    }

    /**
     * @param Padlock $padlock
     */
    public function delete(Padlock $padlock)
    {
        $this->query->newQuery()->where('name', $padlock->getName())->delete();
    }
}
