<?php

namespace Myerscode\PackageDiscovery;

use Myerscode\Utilities\Files\Utility as FileUtility;
use Myerscode\Utilities\Bags\Utility as BagUtility;
use InvalidArgumentException;

class Finder
{
    public function __construct(readonly string $basePath)
    {
        //
    }

    public function vendorPath(): string
    {
        return $this->basePath.'/vendor';
    }

    public function installedPackages(): array
    {
        $packages = [];

        if (($installedPackages = new FileUtility($this->vendorPath().'/composer/installed.json'))->exists()) {
            $installed = json_decode($installedPackages->content(), true);

            $packages = $installed['packages'] ?? [];
        }

        return $packages;
    }

    protected function ignore(string $forPackage): array
    {
        $ignore = [];
        if (($rootPackage = new FileUtility($this->basePath.'/composer.json'))->exists()) {
            $ignore = json_decode($rootPackage->content(), true)['extra'][$forPackage]['avoid'] ?? [];
        }

        return (new BagUtility($ignore))->filter()->value();
    }

    public function discover(string $forPackage): array
    {
        $packages = new BagUtility($this->installedPackages());
        $ignore = $this->ignore($forPackage);

        $shouldIgnoreAll = $ignore == "*" || in_array('*', $ignore);

        $mapper = fn($k, $v) => [$v['name'] => $v['extra'][$forPackage] ?? []];

        $filter = fn($v) => count($v) > 0;
        $filterIgnored = fn($value, $key) => !($shouldIgnoreAll || in_array($key, $ignore));

        return $packages
            ->mapKeys($mapper)
            ->filter($filter)
            ->filter($filterIgnored)
            ->value();
    }

    /**
     * Get the absolute location of package
     *
     * @param  string  $packageName
     *
     * @return string
     */
    public function locate(string $packageName): string
    {
        $packages = (new BagUtility($this->installedPackages()))->mapKeys(fn($k, $v) => [$v['name'] => $v])->value();

        if (!isset($packages[$packageName])) {
            throw new InvalidArgumentException("$packageName is not a known package");
        }

        return str_replace('../', $this->basePath . '/', $packages[$packageName]["install-path"]);
    }
}
