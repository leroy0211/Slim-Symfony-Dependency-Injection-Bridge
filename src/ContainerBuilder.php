<?php

declare(strict_types=1);

namespace Flexsounds\Component\SymfonyContainerSlimBridge;

use Interop\Container\ContainerInterface;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder as BaseContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class ContainerBuilder extends BaseContainerBuilder implements ContainerInterface
{
    public function __construct(ParameterBagInterface $parameterBag = null)
    {
        parent::__construct($parameterBag);
        $this->registerServices($this);
    }

    private function registerServices(self $containerBuilder): void
    {
        $loader = new XmlFileLoader($containerBuilder, new FileLocator(__DIR__.'/Resources'));

        $loader->load('services.xml');
    }
}
