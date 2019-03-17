<?php

declare(strict_types=1);

namespace Flexsounds\Component\SymfonyContainerSlimBridge\Tests;

use Flexsounds\Component\SymfonyContainerSlimBridge\ContainerBuilder;
use Flexsounds\Component\SymfonyContainerSlimBridge\Tests\Fixtures\CustomRouter;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\Dumper\PhpDumper;
use Symfony\Component\DependencyInjection\Exception\RuntimeException;
use Symfony\Component\DependencyInjection\Loader\ClosureLoader;

/**
 * Class ContainerBuilderTest
 * @package Flexsounds\Component\SymfonyContainerSlimBridge\Tests
 */
final class ContainerBuilderTest extends TestCase
{
    /** @var ContainerBuilder */
    private $container;

    protected function setUp(): void
    {
        $container = new ContainerBuilder();
        $this->container = $container;
        $container->compile();
    }

    /**
     * Test if the container is from the Symfony Container Builder instance.
     */
    public function testInstanceOfSymfonyContainerBuilder(): void
    {
        $this->assertInstanceOf('\Symfony\Component\DependencyInjection\ContainerBuilder', $this->container);
    }

    /**
     * Test settings can be edited.
     */
    public function testSettingsCanBeEdited(): void
    {
        $this->assertSame('1.1', $this->container->get('settings')['httpVersion']);
        $this->container->get('settings')['httpVersion'] = '1.2';
        $this->assertSame('1.2', $this->container->get('settings')['httpVersion']);
    }

    /**
     * Test overriding settings on constructor.
     */
    public function testOverrideSettings(): void
    {
        $container = new ContainerBuilder();
        $container->setParameter('httpVersion', '1.3');

        $container->compile();

        $this->assertSame('1.3', $container->get('settings')['httpVersion']);
    }

    public function testCanOverwriteServicesFromLoaders(): void
    {
        $container = new ContainerBuilder();
        $loader = new ClosureLoader($container);
        $loader->load(function (ContainerBuilder $container): void {
            $container->register('router', CustomRouter::class);
        });

        $this->assertInstanceOf(CustomRouter::class, $container->get('router'));
    }

    public function testCanDumpContainer(): void
    {
        try {
            $dumper = new PhpDumper($this->container);

            $dumper->dump();
            $this->assertTrue(true);
        } catch (RuntimeException $exception) {
            $this->fail('Can not dump container. Error: '.$exception->getMessage());
        }
    }
}
