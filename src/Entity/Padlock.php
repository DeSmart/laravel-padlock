<?php

namespace DeSmart\Padlock\Entity;

use Carbon\Carbon;

class Padlock
{
    /** @var string */
    private $name;

    /** @var Carbon */
    private $createdAt;

    /**
     * Padlock constructor.
     * @param $name
     */
    public function __construct(string $name, Carbon $createdAt = null)
    {
        $this->name = $name;
        $this->createdAt = $createdAt ?: Carbon::now(new \DateTimeZone('UTC'));
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return Carbon
     */
    public function getCreatedAt(): Carbon
    {
        return $this->createdAt;
    }
}
