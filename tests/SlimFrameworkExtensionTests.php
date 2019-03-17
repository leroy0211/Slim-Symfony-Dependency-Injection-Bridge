<?php

declare(strict_types=1);

namespace Flexsounds\Component\SymfonyContainerSlimBridge\Tests;

use Flexsounds\Component\SymfonyContainerSlimBridge\SlimFrameworkExtension;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Slim\Handlers\Error;
use Slim\Handlers\NotAllowed;
use Slim\Http\Environment;
use Slim\Http\Response;
use Slim\Router;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class SlimFrameworkExtensionTests extends TestCase
{

    /** @var ContainerBuilder */
    private $container;

    protected function setUp(): void
    {
        $this->container = $container = new ContainerBuilder();
        $container->registerExtension($extension = new SlimFrameworkExtension());
        $container->loadFromExtension($extension->getAlias());
        $container->compile();
    }

    /**
     * Test `get()` returns existing item.
     */
    public function testEnvironment(): void
    {
        $this->assertInstanceOf(Environment::class, $this->container->get('environment'));
    }

    /**
     * Test container has request.
     */
    public function testGetRequest(): void
    {
        $this->assertInstanceOf(RequestInterface::class, $this->container->get('request'));
    }

    /**
     * Test container has response.
     */
    public function testGetResponse(): void
    {
        /** @var Response $response */
        $response = $this->container->get('response');

        $this->assertInstanceOf(ResponseInterface::class, $response);

        $this->assertSame(200, $response->getStatusCode());
        $this->assertSame(['text/html; charset=UTF-8'], $response->getHeader('Content-Type'));
    }

    /**
     * Test container has router.
     */
    public function testGetRouter(): void
    {
        $this->assertInstanceOf(Router::class, $this->container->get('router'));
    }

    /**
     * Test container has error handler.
     */
    public function testGetErrorHandler(): void
    {
        $this->assertInstanceOf(Error::class, $this->container->get('errorHandler'));
    }

    /**
     * Test container has error handler.
     */
    public function testGetNotAllowedHandler(): void
    {
        $this->assertInstanceOf(NotAllowed::class, $this->container->get('notAllowedHandler'));
    }
}
