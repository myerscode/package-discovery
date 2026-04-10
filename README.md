# Package Discovery
> A service to help easily find plugins for your services, using Composer metadata!

[![Latest Stable Version](https://poser.pugx.org/myerscode/package-discovery/v/stable)](https://packagist.org/packages/myerscode/package-discovery)
[![Total Downloads](https://poser.pugx.org/myerscode/package-discovery/downloads)](https://packagist.org/packages/myerscode/package-discovery)
[![PHP Version Require](http://poser.pugx.org/myerscode/package-discovery/require/php)](https://packagist.org/packages/myerscode/package-discovery)
[![License](https://poser.pugx.org/myerscode/package-discovery/license)](https://github.com/myerscode/package-discovery/blob/main/LICENSE)
[![Tests](https://github.com/myerscode/package-discovery/actions/workflows/tests.yml/badge.svg?branch=main)](https://github.com/myerscode/package-discovery/actions/workflows/tests.yml)
[![codecov](https://codecov.io/gh/myerscode/package-discovery/graph/badge.svg)](https://codecov.io/gh/myerscode/package-discovery)


## Requirements

- PHP ^8.5

## Install

```bash
composer require myerscode/package-discovery
```

## Usage

Publishing projects add metadata to their `composer.json` `extra` field. A consuming project instantiates a `Finder`
and uses it to discover, inspect, and locate those packages.

### Publishing a package

Add an object under your namespace key in the `extra` field of `composer.json`:

```json
{
  "name": "myerscode/corgis",
  "extra": {
    "myerscode": {
      "corgis": ["Gerald", "Rupert"],
      "providers": [
        "Myerscode\\Corgis\\CorgiProvider"
      ]
    }
  }
}
```

### Discovering packages

Pass the root path of your project (where `vendor/` lives) to `Finder`, then call `discover()` with your namespace.

```php
$finder = new Finder(__DIR__);

$packages = $finder->discover('myerscode');
```

Returns an array keyed by package name:

```php
[
    'myerscode/corgis' => [
        'corgis' => ['Gerald', 'Rupert'],
        'providers' => ['Myerscode\\Corgis\\CorgiProvider'],
    ],
]
```

You can also discover across multiple namespaces at once by passing an array. Results are merged by package name:

```php
$packages = $finder->discover(['myerscode', 'corgi']);
```

### Avoiding discovery

To exclude a specific package from discovery, add it to the `avoid` list under your namespace in the consuming
project's `composer.json`:

```json
{
  "name": "myerscode/demo-project",
  "extra": {
    "myerscode": {
      "avoid": ["myerscode/corgis"]
    }
  }
}
```

To skip all discoverable packages entirely, use `*`:

```json
{
  "name": "myerscode/demo-project",
  "extra": {
    "myerscode": {
      "avoid": ["*"]
    }
  }
}
```

### Discovering all packages with extras

`discoverAll()` returns every installed package that has any `extra` metadata, regardless of namespace:

```php
$packages = $finder->discoverAll();

// [
//     'myerscode/corgis' => [
//         'myerscode' => [...],
//     ],
// ]
```

### Discovering by Composer package type

`discoverByType()` filters discovery results to packages of a specific Composer `type`:

```php
// Only return packages of type "composer-plugin" that register under the myerscode namespace
$plugins = $finder->discoverByType('composer-plugin', 'myerscode');

// Also works with multiple namespaces
$plugins = $finder->discoverByType('composer-plugin', ['myerscode', 'corgi']);
```

## Checking if a package is installed

`has()` returns `true` if the named package is present in the installed packages list:

```php
if ($finder->has('myerscode/corgis')) {
    // package is installed
}
```

## Listing installed package names

`installedPackageNames()` returns a flat array of all installed package names:

```php
$names = $finder->installedPackageNames();

// ['myerscode/utilities-bags', 'myerscode/corgis', ...]
```

## Locating a package

`locate()` returns the absolute path to a package on disk. Throws `PackageNotFoundException` if the package is
unknown or its directory cannot be resolved:

```php
$path = $finder->locate('myerscode/corgis');

// /var/www/project/vendor/myerscode/corgis
```

## Getting package extras

`packageExtra()` returns the full `extra` array for a package:

```php
$extra = $finder->packageExtra('myerscode/corgis');

// [
//     'myerscode' => [
//         'corgis' => ['Gerald', 'Rupert'],
//         'providers' => ['Myerscode\\Corgis\\CorgiProvider'],
//     ],
// ]
```

## Getting package meta for a service

`packageMetaForService()` returns only the `extra` data scoped to a specific namespace key:

```php
$meta = $finder->packageMetaForService('myerscode/corgis', 'myerscode');

// [
//     'corgis' => ['Gerald', 'Rupert'],
//     'providers' => ['Myerscode\\Corgis\\CorgiProvider'],
// ]
```

## Exceptions

All lookup methods (`locate`, `packageExtra`, `packageMetaForService`) throw
`Myerscode\PackageDiscovery\Exceptions\PackageNotFoundException` when the requested package is not found.
`PackageNotFoundException` extends `InvalidArgumentException`, so existing catch blocks continue to work.

```php
use Myerscode\PackageDiscovery\Exceptions\PackageNotFoundException;

try {
    $path = $finder->locate('vendor/unknown-package');
} catch (PackageNotFoundException $e) {
    // handle missing package
}
```

## Issues

Bug reports and feature requests can be submitted on the [Github Issue Tracker](https://github.com/myerscode/package-discovery/issues).

## Contributing

See the Myerscode [contributing](https://github.com/myerscode/docs/blob/master/contributing.md) page for information.

## License

The MIT License (MIT). Please see [License File](LICENSE) for more information.
