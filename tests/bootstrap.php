<?php

/** @var \Composer\Autoload\ClassLoader $autoloader */
$autoloader = require realpath(__DIR__."/../vendor/autoload.php");

$autoloader->addPsr4('Flexsounds\Component\SymfonyContainerSlimBridge\Tests\\', __DIR__);
