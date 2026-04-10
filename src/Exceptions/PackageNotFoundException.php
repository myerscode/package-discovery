<?php

declare(strict_types=1);

namespace Myerscode\PackageDiscovery\Exceptions;

use InvalidArgumentException;

class PackageNotFoundException extends InvalidArgumentException
{
    public static function forPackage(string $packageName): self
    {
        return new self($packageName . ' is not a known package');
    }
}
