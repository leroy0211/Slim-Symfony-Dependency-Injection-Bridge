[![Travis Build Status](https://img.shields.io/travis/leroy0211/Slim-Symfony-Dependency-Injection-Bridge/master.svg?maxAge=2592000?style=flat-square)](https://travis-ci.org/leroy0211/Slim-Symfony-Dependency-Injection-Bridge)
[![Packagist](https://img.shields.io/packagist/v/flexsounds/slim-symfony-di-container.svg?maxAge=2592000?style=flat-square)](https://packagist.org/packages/flexsounds/slim-symfony-di-container)
[![Packagist](https://img.shields.io/packagist/dt/flexsounds/slim-symfony-di-container.svg?maxAge=2592000?style=flat-square)](https://packagist.org/packages/flexsounds/slim-symfony-di-container)

# Slim-Symfony-Dependency-Injection-Bridge
Just a simple bridge to use the Symfony Dependency Injection Container to replace the Container in Slim Framework 3

This will replace the `pimple` container which comes default with Slim Framework 3.

The default services (like `router`, `request`, `response`) which Slim Framework uses, are preloaded in the `ContainerBuilder`. This way Slim will work as it should.

# Installation
Use composer to install

`composer require flexsounds/slim-symfony-di-container`

# Default usage
To use the Symfony DI Container just add the ContainerBuilder to `Slim\App`

```php
$container = new \Flexsounds\Component\SymfonyContainerSlimBridge\ContainerBuilder();

$app = new \Slim\App($container);

// define your routes here. The container is available through $this in the route closure

$app->run();
```

# Other Examples

## Example loading your dependencies through yaml configuration (The Symfony way)

```php
$container = new \Flexsounds\Component\SymfonyContainerSlimBridge\ContainerBuilder();
$loader = $loader = new \Symfony\Component\DependencyInjection\Loader\YamlFileLoader($container, new \Symfony\Component\Config\FileLocator(__DIR__));
$loader->load('config.yml');

$app = new \Slim\App($container);

$app->run();

````

Now you can create a `config.yml` file to load your services, parameters, etc. [The use of importing other config files is also available.](http://symfony.com/doc/current/cookbook/configuration/configuration_organization.html#different-directories-per-environment) 

```yml
services:
  my.custom.service:
    class: Location\To\The\Class
```

Now the service `my.custom.service` is available in the container. Use `$this->get('my.custom.service')` to load the service.

```php
$app->get('/', function($request, $response){
  $customService = $this->get('my.custom.service'); // $customService is now an instance of Location\To\The\Class()
});
```


# Read more
Read the [symfony service container documentation](http://symfony.com/doc/current/book/service_container.html) to find out what other options are available in the service container.

Read the [symfony dependency injection documentation](http://symfony.com/doc/current/components/dependency_injection/introduction.html) to find out how the ContainerBuilder is used. Like setting default parameters.

# Interesting to know
If you use PhpStorm as IDE and add the Symfony Plugin, typehinting for services should be available. 
