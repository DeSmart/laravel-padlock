<?php

namespace DeSmart\Padlock\Console;

use DeSmart\Padlock\Entity\Padlock;
use DeSmart\Padlock\PadlockHandler;
use Illuminate\Console\Command;

class ListCommand extends Command
{
    protected $signature = 'padlock:list';

    protected $description = 'Lists existing padlocks with their time of creation';

    /** @var PadlockHandler */
    private $padlockHandler;

    /**
     * UnlockCommand constructor.
     * @param PadlockHandler $padlockHandler
     */
    public function __construct(PadlockHandler $padlockHandler)
    {
        parent::__construct();

        $this->padlockHandler = $padlockHandler;
    }

    public function handle()
    {
        $this->padlockHandler->getAll()->each(function(Padlock $padlock) {
            echo $padlock->getName() . ": " . $padlock->getCreatedAt()->toDateTimeString() . PHP_EOL;
        });
    }
}
