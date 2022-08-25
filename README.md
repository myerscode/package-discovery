# Package Discovery
> A service to help easily find plugins for your services, using Composer metadata!

[![Latest Stable Version](https://poser.pugx.org/myerscode/package-discovery/v/stable)](https://packagist.org/packages/myerscode/package-discovery)
[![Total Downloads](https://poser.pugx.org/myerscode/package-discovery/downloads)](https://packagist.org/packages/myerscode/package-discovery)
[![License](https://poser.pugx.org/myerscode/package-discovery/license)](https://packagist.org/packages/myerscode/package-discovery)

## Install

You can install this package via composer:

``` bash
composer require myerscode/package-discovery
```

## Usage

Using the `Finder` class, initiate passing in the root path, relative to the `vendor` directory.

Then use the `discover` method to find all packages that have the given name in its extras field. 

```php
$finder = new Finder(__DIR__);

// would find all installed packages that myerscode in the extras
$packages = $finder->discover('myerscode');
```

```json
// example extras field in package.json
{
  ...
  "extra": {
    "myerscode": {
      "corgis": ["Gerald", "Rupert"]
    }
  }
}
```
