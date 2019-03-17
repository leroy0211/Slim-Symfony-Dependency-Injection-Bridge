<?php declare(strict_types=1);

namespace Flexsounds\Component\SymfonyContainerSlimBridge;

use Slim\Http\Environment;

final class EnvironmentFactory
{
    public static function build()
    {
        return new Environment($_SERVER);
    }
}
