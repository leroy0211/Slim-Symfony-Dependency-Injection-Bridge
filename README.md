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
$loader = $loader = new \Symfony\Component\DependencyInjection\Loader\YamlFileLoader($container, new \Symfony\Component\Config\FileLocator($configPath));
$loader->load('config.yml');

$app = new \Slim\App($container);

$app->run();

````
