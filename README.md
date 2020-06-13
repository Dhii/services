[![Build Status](https://travis-ci.org/Dhii/services.svg?branch=develop)](https://travis-ci.org/Dhii/services)
[![This package complies with Dhii standards](https://img.shields.io/badge/Dhii-Compliant-green.svg?style=flat-square)][Dhii]

# Dhii - Services

This package provides a collection of service factory and extension definition implementations that can be used with [PSR-11 containers][psr11], as well as the experimental [service provider spec][sp], to replace the anonymous functions that are typically used for definitions.

----

- [Requirements](#requirements)
- [Installation](#installation)
- [Classes](#classes)
  - [`Factory`](#factory)
  - [`Extension`](#extension)
  - [`Constructor`](#constructor)
  - [`ServiceList`](#servicelist)
  - [`ArrayExtension`](#arrayextension)
  - [`FuncService`](#funcservice)
  - [`Others`](#others)
- [Mutation](#mutation)
- [Multi-Boxing](#multi-boxing)
- [Static Analysis](#static-analysis)

# Requirements
- PHP >= 7.0

# Installation

**With [Composer][composer]**:

```
composer require dhii/services
```

**Without Composer**:

1. Go [here][composer].
2. Install it.
3. See "With Composer"

# Classes

All implementations in this package inherit from [`Service`][Service]; an [invocable][__invoke] object with a [`getDependencies`][getDeps] method that returns an array of keys.

## [`Factory`][Factory]

A simple implementation that uses a callback to construct its service.

Unlike a normal anonymous function, the callback given to the `Factory` does not get a `ContainerInterface` argument, but rather the services that match the given dependency keys.

```php
new Factory(['dep1', 'dep2'], function($dep1, $dep2) {
  // ...
});
```

Equivalent to:

```php
function (ContainerInterface $c) {
  $dep1 = $c->get('dep1');
  $dep2 = $c->get('dep2');
  // ...
}
```


## [`Extension`][Extension]

Very similar to `Factory`, but the callback also receives the service instance from the original factory or previous extension as the first argument.

```php
new Extension(['dep1', 'dep2'], function($prev, $dep1, $dep2) {
  // ...
});
```

Equivalent to:

```php
function (ContainerInterface $c, $prev) {
  $dep1 = $c->get('dep1');
  $dep2 = $c->get('dep2');
  // ...
}
```

## [`Constructor`][Constructor]

A variant of `Factory` that invokes a constructor, rather than a callback function. Very useful in cases where a class is only constructed using other services.

```php
new Constructor(MyClass::class, ['dep1', 'dep2']);
```

Equivalent to:

```php
function (ContainerInterface $c) {
  $dep1 = $c->get('dep1');
  $dep2 = $c->get('dep2');

  return new MyClass($dep1, $dep2);
}
```

Consequently, it also works without any dependencies:

```php
new Constructor(MyClass::class);
```

Equivalent to:

```php
function (ContainerInterface $c) {
  return new MyClass();
}
```

## [`ServiceList`][ServiceList]

Creates an array that contains the services indicated by its dependencies. Very useful for managing registration of instances when coupled with [`ArrayExtension`].

```php
new ServiceList('service1', 'service2']);
```

Equivalent to:

```php
function (ContainerInterface $c) {
  return [
    $c->get('service1'),
    $c->get('service2'),
  ];
}
```

## [`ArrayExtension`][ArrayExtension]

An extension implementation that adds its dependencies to the previous value. Very useful for registering new instances to a list.

```php
new ArrayExtension(['dep1', 'dep2'])
```

Equivalent to:

```php
function (ContainerInterface $c, $prev) {
  return array_merge($prev, [
    $c->get('dep1'),
    $c->get('dep2'),
  ]);
}
```

## [`FuncService`][FuncService]

A variant of factory, but it returns the callback rather than invoking it. Invocation arguments will be passed before the injected dependencies. Very useful for declaring callback services.

```php
new FuncService(['dep1', 'dep2'], function($arg1, $arg2, $dep1, $dep2) {
  // ...
});
```

Equivalent to:

```php
function (ContainerInterface $c) {
  $dep1 = $c->get('dep1');
  $dep2 = $c->get('dep2');

  return function ($arg1, $arg2) use ($dep1, $dep2) {
    // ...
  };
}
```

## Others

* [`StringService`][StringService] - For services that return strings that are interpolated with other services.
* [`Value`][Value] - For services that always return a static value.
* [`Alias`][Alias] - An alias for another service, with defaulting capabilities for when the original does not exist.
* [`GlobalVar`][GlobalVar] - For services that return global variables.

# Mutation

The [`withDependencies()`][withDeps] method allows all service instances to be copied with different dependencies, while leaving the origianl instances unaffected.

```php
$service = new Factory(['database'], function () {
  // ...
});

$service2 = $service->withDependencies(['db']);
```

This makes it possible to modify service dependencies at run-time, or even during a build process, which can be especially useful when dealing with 3rd party service providers that need to be rewired.

# Multi-boxing

One of the benefits of being able to derive new services with different dependencies is the ability to use the same provider multiple times. Let's look at an example.

Consider a service provider for a logger that writes to a file.

```php
class LoggerProvider implements ServiceProviderInterface
{
  public function getFactories()
  {
    'logger' => new Constructor(FileLogger::class, ['file_path']),
    'file_path' => new Value(sys_get_tmp_dir() . '/log.txt')
  }
  
  public function getExtensions ()
  {
    return [];
  }
}
```

Our application needs to keep 2 different log files: one for errors and one for debugging.

Simply using the above service provider twice won't work; we'd be re-declaring the `logger` and `file_path` services.

Prefixing the factories _would_ allow us to have two instances of the service provider, but that would break the dependencies. If we prefixed the factories from one logger such that they become `debug_logger` and `debug_file_path`, the `debug_logger` factory would still be depending on `file_path`, which would no longer exist after prefixing.

This where mutation of dependencies comes in. We can write a `PrefixingProvider` decorator that not only prefixes all services in a provider, but also prefixes any dependencies.

_(The below class is incomplete for the sake of brevity. Assume that a constructor exists and that it initializes its `$prefix` and `$provider` properties)_.

```php
class PrefixingProvider implements ServiceProviderInterface {
  public function getFactories() {
    $factories = [];

    foreach ($this->provider->getFactories() as $key => $factory) {
      $deps = $factory->getDependencies();
      $newDeps = array_map(fn($dep) => $this->prefix . $dep, $deps);

      $factories[$this->prefix . $key] = $factory->withDependencies($newDeps);
    }
    
    return $factories;
  }
}
```

We can now create two different versions of the same service provider:

```php
$debugLogProvider = new PrefixingProvider('debug_', new LoggerProvider);
$errorLogProvider = new PrefixingProvider('error_', new LoggerProvider);
```

The first one will provide `debug_logger` and `debug_file_path`, while the second will provide `error_logger` and `error_file_path`.

# Static Analysis

By having all services declare their dependencies, we open up the possibility to create an inspection tool that statically analyzes a list of services to build a dependency graph. This graph can help uncover various potential problems without needing to run the code. These insights can reveal:

* Circular dependency
* Dependencies that do not exist
* Factories that override each other, rather than using extensions
* Dependency chains that are too deep
* Unused services

No such tool exists at the time of writing, but I _do_ plan on taking on this task.

[Dhii]: https://github.com/Dhii/dhii
[psr11]: https://github.com/php-fig/container
[sp]: https://github.com/container-interop/service-provider
[composer]: https://getcomposer.org/
[__invoke]: https://www.php.net/manual/en/language.oop5.magic.php#object.invoke
[getDeps]: https://github.com/Dhii/services/blob/initial-version/src/Service.php#L49
[withDeps]: https://github.com/Dhii/services/blob/initial-version/src/Service.php#L63
[Service]: src/Service.php
[Factory]: src/Factory.php
[Extension]: src/Extension.php
[Constructor]: src/Factories/Constructor.php
[ServiceList]: src/Factories/ServiceList.php
[ArrayExtension]: src/Extensions/ArrayExtension.php
[FuncService]: src/Factories/FuncService.php
[StringService]: src/Factory/StringService.php
[Value]: src/Factories/Value.php
[Alias]: src/Factory/Alias.php
[GlobalVar]: src/Factory/GlobalVar.php
