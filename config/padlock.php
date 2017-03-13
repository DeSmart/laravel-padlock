<?php

use DeSmart\Padlock\Driver;

return [
    // Allows for quickly disabling the entire Padlock mechanism.
    // Useful for script development, if you run your script often and don't make it reach the unlocking yet.
    'enabled' => true,

    // choose one of the drivers
    'driver' => Driver\DatabaseDriver::class,
//    'driver' => Driver\FilesystemDriver::class,
];