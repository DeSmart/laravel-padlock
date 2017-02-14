<?php

namespace DeSmart\Padlock\Model;

use Carbon\Carbon;
use DeSmart\Padlock\Entity\Padlock;
use Illuminate\Database\Eloquent\Model;

class PadlockModel extends Model
{
    public $incrementing = false;

    public $timestamps = false;

    protected $table = 'padlocks';

    protected $dates = ['created_at'];

    /**
     * @return Padlock
     */
    public function toEntity()
    {
        return new Padlock($this->attributes['name'], new Carbon($this->attributes['created_at']));
    }

    /**
     * @param Padlock $padlock
     * @return PadlockModel
     */
    public function createFromEntity(Padlock $padlock)
    {
        $attributes = [
            'name' => $padlock->getName(),
            'created_at' => $padlock->getCreatedAt()->toDateTimeString()
        ];

        static::unguard();
        $model = (new static)->newInstance($attributes);
        static::reguard();

        return $model;
    }
}
