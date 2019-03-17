<?php

declare(strict_types=1);

namespace Flexsounds\Component\SymfonyContainerSlimBridge\Tests;

use Flexsounds\Component\SymfonyContainerSlimBridge\ContainerBuilder;
use Flexsounds\Component\SymfonyContainerSlimBridge\Tests\Fixtures\CustomRouter;
use PHPUnit\Framework\TestCase;
use Slim\Http\Response;
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
     * Test if the container is from the Interop Container Interface, Slim uses.
     */
    public function testInstanceOfSlimContainerInterface(): void
    {
        $this->assertInstanceOf('\Interop\Container\ContainerInterface', $this->container);
    }

    /**
     * Test `get()` returns existing item.
     */
    public function testGet(): void
    {
        $this->assertInstanceOf('\Slim\Http\Environment', $this->container->get('environment'));
    }

    /**
     * Test container has request.
     */
    public function testGetRequest(): void
    {
        $this->assertInstanceOf('\Psr\Http\Message\RequestInterface', $this->container->get('request'));
    }

    /**
     * Test container has response.
     */
    public function testGetResponse(): void
    {
        /** @var Response $response */
        $response = $this->container->get('response');

        $this->assertInstanceOf('\Psr\Http\Message\ResponseInterface', $response);

        $this->assertSame(200, $response->getStatusCode());
        $this->assertSame(['text/html; charset=UTF-8'], $response->getHeader('Content-Type'));
    }

    /**
     * Test container has router.
     */
    public function testGetRouter(): void
    {
        $this->assertInstanceOf('\Slim\Router', $this->container->get('router'));
    }

    /**
     * Test container has error handler.
     */
    public function testGetErrorHandler(): void
    {
        $this->assertInstanceOf('\Slim\Handlers\Error', $this->container->get('errorHandler'));
    }

    /**
     * Test container has error handler.
     */
    public function testGetNotAllowedHandler(): void
    {
        $this->assertInstanceOf('\Slim\Handlers\NotAllowed', $this->container->get('notAllowedHandler'));
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
