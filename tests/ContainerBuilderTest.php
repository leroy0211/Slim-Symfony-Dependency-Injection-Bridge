<?php

namespace Flexsounds\Component\SymfonyContainerSlimBridge\Tests;

use Flexsounds\Component\SymfonyContainerSlimBridge\ContainerBuilder;
use Flexsounds\Component\SymfonyContainerSlimBridge\Tests\Fixtures\CustomRouter;
use Slim\Http\Response;
use Symfony\Component\DependencyInjection\Dumper\PhpDumper;
use Symfony\Component\DependencyInjection\Exception\RuntimeException;
use Symfony\Component\DependencyInjection\Loader\ClosureLoader;

final class ContainerBuilderTest extends \PHPUnit_Framework_TestCase
{
    /** @var ContainerBuilder */
    private $container;

    protected function setUp()
    {
        $container = new ContainerBuilder();
        $this->container = $container;
        $container->compile();
    }

    /**
     * Test if the container is from the Symfony Container Builder instance.
     */
    public function testInstanceOfSymfonyContainerBuilder()
    {
        $this->assertInstanceOf('\Symfony\Component\DependencyInjection\ContainerBuilder', $this->container);
    }

    /**
     * Test if the container is from the Interop Container Interface, Slim uses.
     */
    public function testInstanceOfSlimContainerInterface()
    {
        $this->assertInstanceOf('\Interop\Container\ContainerInterface', $this->container);
    }

    /**
     * Test `get()` returns existing item.
     */
    public function testGet()
    {
        $this->assertInstanceOf('\Slim\Http\Environment', $this->container->get('environment'));
    }

    /**
     * Test container has request.
     */
    public function testGetRequest()
    {
        $this->assertInstanceOf('\Psr\Http\Message\RequestInterface', $this->container->get('request'));
    }

    /**
     * Test container has response.
     */
    public function testGetResponse()
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
    public function testGetRouter()
    {
        $this->assertInstanceOf('\Slim\Router', $this->container->get('router'));
    }

    /**
     * Test container has error handler.
     */
    public function testGetErrorHandler()
    {
        $this->assertInstanceOf('\Slim\Handlers\Error', $this->container->get('errorHandler'));
    }

    /**
     * Test container has error handler.
     */
    public function testGetNotAllowedHandler()
    {
        $this->assertInstanceOf('\Slim\Handlers\NotAllowed', $this->container->get('notAllowedHandler'));
    }

    /**
     * Test settings can be edited.
     */
    public function testSettingsCanBeEdited()
    {
        $this->assertSame('1.1', $this->container->get('settings')['httpVersion']);
        $this->container->get('settings')['httpVersion'] = '1.2';
        $this->assertSame('1.2', $this->container->get('settings')['httpVersion']);
    }

    /**
     * Test overriding settings on constructor.
     */
    public function testOverrideSettings()
    {
        $container = new ContainerBuilder();
        $container->setParameter('httpVersion', '1.3');

        $container->compile();

        $this->assertSame('1.3', $container->get('settings')['httpVersion']);
    }

    public function testCanOverwriteServicesFromLoaders()
    {
        $container = new ContainerBuilder();
        $loader = new ClosureLoader($container);
        $loader->load(function (ContainerBuilder $container) {
            $container->register('router', CustomRouter::class);
        });

        $this->assertInstanceOf(CustomRouter::class, $container->get('router'));
    }

    public function testCanDumpContainer()
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
