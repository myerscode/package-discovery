# Package Discovery
> A service to help easily find plugins for your services, using Composer metadata!

[![Latest Stable Version](https://poser.pugx.org/myerscode/package-discovery/v/stable)](https://packagist.org/packages/myerscode/package-discovery)
[![Total Downloads](https://poser.pugx.org/myerscode/package-discovery/downloads)](https://packagist.org/packages/myerscode/package-discovery)
[![License](https://poser.pugx.org/myerscode/package-discovery/license)](https://packagist.org/packages/myerscode/package-discovery)
![Tests](https://github.com/myerscode/package-discovery/workflows/tests/badge.svg?branch=main)


## Install

You can install this package via composer:

``` bash
composer require myerscode/package-discovery
```

## Usage

Publishing projects just need to add appropriate metadata in their package, which can be then detected by a consuming 
project. A project which wants to disover projects will need to instantiate a `Finder` to look up the project namespace.
You will then be able to consume the found metadata in the project as desired.

### Publishing project

In your `package.json` file, add a object in the `extras` object, with a key that relates to the project namespace you
want to discover it.

```json
{
  "extra": {
    "name": "myerscode/corgis",
    "myerscode": {
      "corgis": ["Gerald", "Rupert"],
      "providers": [
        "Myerscode\\Corgis\\CorgiProvider"
      ]
    }
  }
}
```

### Consuming project

Using the `Finder` class, initiate passing in the root path, relative to the `vendor` directory.

Then use the `discover` method to find all packages that have the given name in its extras field.

```php
$finder = new Finder(__DIR__);

// would find all installed packages that have a myerscode namespace in the extras
$packages = $finder->discover('myerscode');
```

After discovering package you would have an array of metadata for each one discovered.

```php
[
  "myerscode/corgis" => [
      "corgis": ["Gerald", "Rupert"],
      "providers": [
        "Myerscode\\Corgis\\CorgiProvider"
      ]
  ]
]
```

### Avoiding discovery

If you don't want to discover a specific project, then you can add some metadata in the consuming package to prevent this.

You would do this by adding the package name to `avoid` under the projects namespace in the extras field of `package.json`.

```json
{
  "extra": {
    "name": "myerscode/demo-project",
    "myerscode": {
      "avoid": [
        "myerscode/corgis"
      ]
    }
  }
}
```

If you want to avoid loading in all discoverable packages, simply add `*` in the avoid field.

```json
{
  "extra": {
    "name": "myerscode/demo-project",
    "myerscode": {
      "avoid": [ "*" ]
    }
  }
}
```

## Locating a package

When you want to find out where a pacakge is located on the disk, you can use the `locate` method to look up its absolute 
path.

```php 
$finder = new Finder(__DIR__);

echo $finder->locate('myerscode/test-package');

// /User/fred/project-name/vendor/myerscode/test-package
```

## Issues

Bug reports and feature requests can be submitted on the [Github Issue Tracker](https://github.com/myerscode/package-discovery/issues).

## Contributing

See the Myerscode [contributing](https://github.com/myerscode/docs/blob/master/contributing.md) page for information.

## License

The MIT License (MIT). Please see [License File](LICENSE) for more information.
