<?php

// Setup autoload
require __DIR__ . '/' . '../vendor/autoload.php';

// Register the testing class.
$classLoader = new \Composer\Autoload\ClassLoader();
$classLoader->addPsr4("BehatParserTest\\Fixtures\\", __DIR__ . '/BehatParserTest/Fixtures', true);
$classLoader->register();
