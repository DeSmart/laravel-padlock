# Padlock Script Locker for Laravel framework

[![Build Status](https://travis-ci.org/DeSmart/laravel-padlock.svg?branch=master)](https://travis-ci.org/DeSmart/laravel-padlock)

This package allows for easily temporarily locking your scripts execution.
 
It might come in handy in cases such as CRON jobs that connect with unreliable APIS, where you're not 100% sure if your script won't fail at some point.

## Requirements
This package requires:
* PHP >= 7.0.0
* Laravel 5.3.x

## Installation
1. `$ composer require desmart/padlock`
2. Add `DeSmart\Padlock\ServiceProvider` to your `config/app.php`:
```
        /*
         * Package Service Providers...
         */
        DeSmart\Padlock\ServiceProvider::class,
```

## Example usage

This package's purpose is to protect your script from being run on multiple threads.

It is useful for long-time backend jobs such as handling queries, or harvesting data from external APIs.

```
class FooCommand extends \Illuminate\Console\Command
{
    protected $signature = 'foo:bar';

    protected $description = 'Foo command utilizing Padlock';

    /** @var PadlockHandler */
    private $padlockHandler;

    const PADLOCK_SCRIPT = 'FooCommand';

    /** 30 seconds padlock time to live - after that your padlock will be unlocked */
    const PADLOCK_TTL = 30;

    /**
     * FooCommand constructor.
     * @param \DeSmart\Padlock\PadlockHandler $padlockHandler
     */
    public function __construct(\DeSmart\Padlock\PadlockHandler $padlockHandler)
    {
        parent::__construct();

        $this->padlockHandler = $padlockHandler;
    }

    public function handle()
    {
        if (true === $this->padlockHandler->isLocked(self::PADLOCK_SCRIPT, self::PADLOCK_TTL)) {
            echo "Padlock exists, script locked." . PHP_EOL;

            return;
        }

        $this->padlockHandler->lock(self::PADLOCK_SCRIPT);
        
        // do your stuff
        
        $this->padlockHandler->unlock(self::PADLOCK_SCRIPT);
    }
}
```
