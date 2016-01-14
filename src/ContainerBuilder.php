<?php
/**
 * Created by PhpStorm.
 * User: leroy
 * Date: 14/01/16
 * Time: 10:41
 */

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
        $this->registerDefaultServices($this->defaultSettings);
    }

    private function registerDefaultServices($settings)
    {
        $this->set('settings', new Collection($settings));

        $this->set('environment', new Environment($_SERVER));

        $this->set('request', Request::createFromEnvironment($this->get('environment')));

        $this->set('response', (new Response(200, new Headers(['Content-Type' => 'text/html; charset=UTF-8'])))->withProtocolVersion($settings['httpVersion']));

        $this->set('router', new Router());

        $this->set('foundHandler', new RequestResponse());

        $this->set('errorHandler', new Error($settings['displayErrorDetails']));

        $this->set('notFoundHandler', new NotFound());

        $this->set('notAllowedHandler', new NotAllowed());

        $this->set('callableResolver', new CallableResolver($this));
    }

}
