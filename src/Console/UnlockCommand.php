<?php

namespace DeSmart\Padlock\Console;

use DeSmart\Padlock\PadlockHandler;
use Illuminate\Console\Command;

class UnlockCommand extends Command
{
    protected $signature = 'padlock:unlock {scriptName}';

    protected $description = 'Forces unlocking padlock for given name';

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
        $name = $this->argument('scriptName');

        if (false === $this->padlockHandler->isLocked($name)) {
            echo "There is no padlock on script '{$name}'" . PHP_EOL;

            return;
        }

        $this->padlockHandler->unlock($name);

        echo "Padlock removed for script '{$name}'" . PHP_EOL;
    }
}
