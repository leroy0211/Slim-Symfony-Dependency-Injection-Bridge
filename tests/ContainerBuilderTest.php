<?php

namespace Flexsounds\Component\SymfonyContainerSlimBridge\Tests;

use Flexsounds\Component\SymfonyContainerSlimBridge\ContainerBuilder;
use Flexsounds\Component\SymfonyContainerSlimBridge\Tests\Stubs\CustomRouter;
use Symfony\Component\DependencyInjection\Loader\ClosureLoader;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBag;

/**
 * Class ContainerBuilderTest
 * @package Flexsounds\Component\SymfonyContainerSlimBridge\Tests
 */
class ContainerBuilderTest extends \PHPUnit_Framework_TestCase
{
    /** @var ContainerBuilder */
    private $container;

    protected function setUp()
    {
        $container       = new ContainerBuilder();
        $this->container = $container;
    }

    /**
     * Test if the container is from the Symfony Container Builder instance
     */
    public function testInstanceOfSymfonyContainerBuilder()
    {
        $this->assertInstanceOf('\Symfony\Component\DependencyInjection\ContainerBuilder', $this->container);
    }

    /**
     * Test if the container is from the Interop Container Interface, Slim uses
     */
    public function testInstanceOfSlimContainerInterface()
    {
        $this->assertInstanceOf('\Interop\Container\ContainerInterface', $this->container);
    }

    /**
     * Test `get()` returns existing item
     */
    public function testGet()
    {
        $this->assertInstanceOf('\Slim\Http\Environment', $this->container->get('environment'));
    }

    /**
     * Test `get()` throws error if item does not exist
     *
     * @expectedException \Symfony\Component\DependencyInjection\Exception\ServiceNotFoundException
     */
    public function testGetWithValueNotFoundError()
    {
        $this->container->get('foo');
    }

    /**
     * Test container has request
     */
    public function testGetRequest()
    {
        $this->assertInstanceOf('\Psr\Http\Message\RequestInterface', $this->container->get('request'));
    }

    /**
     * Test container has response
     */
    public function testGetResponse()
    {
        $this->assertInstanceOf('\Psr\Http\Message\ResponseInterface', $this->container->get('response'));
    }

    /**
     * Test container has router
     */
    public function testGetRouter()
    {
        $this->assertInstanceOf('\Slim\Router', $this->container->get('router'));
    }

    /**
     * Test container has error handler
     */
    public function testGetErrorHandler()
    {
        $this->assertInstanceOf('\Slim\Handlers\Error', $this->container->get('errorHandler'));
    }

    /**
     * Test container has error handler
     */
    public function testGetNotAllowedHandler()
    {
        $this->assertInstanceOf('\Slim\Handlers\NotAllowed', $this->container->get('notAllowedHandler'));
    }

    /**
     * Test settings can be edited
     */
    public function testSettingsCanBeEdited()
    {
        $this->assertSame('1.1', $this->container->get('settings')['httpVersion']);
        $this->container->get('settings')['httpVersion'] = '1.2';
        $this->assertSame('1.2', $this->container->get('settings')['httpVersion']);
    }

    /**
     * Test overriding settings on constructor
     */
    public function testOverrideSettingsOnConstruct()
    {
        $container = new ContainerBuilder(new ParameterBag(array(
            'httpVersion' => "1.3"
        )));

        $this->assertSame('1.3', $container->get('settings')['httpVersion']);
    }

    public function testCanOverwriteServicesFromLoaders()
    {
        $container = new ContainerBuilder();
        $loader    = new ClosureLoader($container);
        $loader->load(function (ContainerBuilder $container) {
            $container->register('router', CustomRouter::class);
        });

        $this->assertInstanceOf(CustomRouter::class, $container->get('router'));
    }

}