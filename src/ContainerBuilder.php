<?php

namespace Flexsounds\Component\SymfonyContainerSlimBridge;

use Interop\Container\ContainerInterface;
use Slim\CallableResolver;
use Slim\Collection;
use Slim\Handlers\Error;
use Slim\Handlers\NotAllowed;
use Slim\Handlers\NotFound;
use Slim\Handlers\Strategies\RequestResponse;
use Slim\Http\Environment;
use Slim\Http\Headers;
use Slim\Http\Request;
use Slim\Http\Response;
use Slim\Router;
use Symfony\Component\DependencyInjection\ContainerBuilder as BaseContainerBuilder;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\DependencyInjection\Reference;

class ContainerBuilder extends BaseContainerBuilder implements ContainerInterface
{
    private $defaultSettings = [
        'httpVersion' => '1.1',
        'responseChunkSize' => 4096,
        'outputBuffering' => 'append',
        'determineRouteBeforeAppMiddleware' => false,
        'displayErrorDetails' => false,
    ];

    public function __construct(ParameterBagInterface $parameterBag = null)
    {
        parent::__construct($parameterBag);

        foreach ($this->defaultSettings as $key => $value) {
            if (!$this->getParameterBag()->has($key)) {
                $this->getParameterBag()->set($key, $value);
            }
        }

        $this->registerDefaultServices();
    }

    private function registerDefaultServices()
    {
        $this->register('settings', Collection::class)
            ->setPublic(true)
            ->addArgument([
            'httpVersion' => '%httpVersion%',
            'responseChunkSize' => '%responseChunkSize%',
            'outputBuffering' => '%outputBuffering%',
            'determineRouteBeforeAppMiddleware' => '%determineRouteBeforeAppMiddleware%',
            'displayErrorDetails' => '%displayErrorDetails%',
        ]);

        $this->register('environment', Environment::class)
            ->setPublic(true)
             ->addArgument($_SERVER);

        $this->register('request', Request::class)
            ->setPublic(true)
            ->setFactory([Request::class, 'createFromEnvironment'])
            ->addArgument(new Reference('environment'));

        $this->register('response.headers', Headers::class)
            ->addArgument(['Content-Type' => 'text/html; charset=UTF-8']);

        $this->register('response', Response::class)
            ->setPublic(true)
            ->addArgument(200)
            ->addArgument(new Reference('response.headers'))
            ->addMethodCall('withProtocolVersion', ['%httpVersion%'])
            ;

        $this->register('router', Router::class)
            ->setPublic(true);

        $this->register('foundHandler', RequestResponse::class)
            ->setPublic(true);

        $this->register('errorHandler', Error::class)
            ->setPublic(true)
            ->addArgument('%displayErrorDetails%');

        $this->register('notFoundHandler', NotFound::class)
            ->setPublic(true);

        $this->register('notAllowedHandler', NotAllowed::class)
            ->setPublic(true);

        $this->register('callableResolver', CallableResolver::class)
            ->setPublic(true)
            ->addArgument(new Reference('service_container'));
    }
}
