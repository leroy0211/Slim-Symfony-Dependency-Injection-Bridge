<?php

declare(strict_types=1);

namespace Flexsounds\Component\SymfonyContainerSlimBridge;

use Symfony\Component\DependencyInjection\ContainerBuilder as BaseContainerBuilder;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class ContainerBuilder extends BaseContainerBuilder
{
    public function __construct(ParameterBagInterface $parameterBag = null)
    {
        parent::__construct($parameterBag);
        $this->registerExtension($extension = new SlimFrameworkExtension());
        $this->loadFromExtension($extension->getAlias());
    }
}
