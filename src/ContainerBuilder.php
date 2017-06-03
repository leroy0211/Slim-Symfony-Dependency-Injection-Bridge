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

        foreach($this->defaultSettings as $key => $value){
            if(!$this->getParameterBag()->has($key)){
                $this->getParameterBag()->set($key, $value);
            }
        }

        $this->registerDefaultServices();
    }

    private function registerDefaultServices()
    {
        $definition = $this->register('settings', Collection::class);
        $definition->addArgument(array(
            'httpVersion' => '%httpVersion%',
            'responseChunkSize' => '%responseChunkSize%',
            'outputBuffering' => '%outputBuffering%',
            'determineRouteBeforeAppMiddleware' => '%determineRouteBeforeAppMiddleware%',
            'displayErrorDetails' => '%displayErrorDetails%',
        ));

        $this->set('environment', new Environment($_SERVER));

        $this->set('request', Request::createFromEnvironment($this->get('environment')));

        $this->register('response', Response::class)
            ->addArgument(200)
            ->addArgument(new Headers(['Content-Type' => 'text/html; charset=UTF-8']))
            ->addMethodCall('withProtocolVersion', array('%httpVersion%'))
            ;

        $this->set('router', new Router());

        $this->set('foundHandler', new RequestResponse());

        $this->register('errorHandler', Error::class)
            ->addArgument('%displayErrorDetails%');

        $this->set('notFoundHandler', new NotFound());

        $this->set('notAllowedHandler', new NotAllowed());

        $this->set('callableResolver', new CallableResolver($this));
    }

}
