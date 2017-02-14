<?php

use DeSmart\Padlock\Driver;

return [
    // choose one of the drivers
    'driver' => Driver\DatabaseDriver::class,
//    'driver' => Driver\FilesystemDriver::class,
];