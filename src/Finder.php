<?php

declare(strict_types=1);

namespace Myerscode\PackageDiscovery;

use InvalidArgumentException;
use Myerscode\Utilities\Bags\Utility as BagUtility;
use Myerscode\Utilities\Files\Utility as FileUtility;

readonly class Finder
{
    public function __construct(public string $basePath, public string $vendorDirectory = 'vendor')
    {
        //
    }

    /**
     * Discover packages wanting to interact with your service
     *
     * @return array<string, array<string, mixed>>
     */
    public function discover(string $forPackage): array
    {
        $utility = new BagUtility($this->installedPackages());

        $ignore = $this->ignore($forPackage);

        $shouldIgnoreAll = $ignore == '*' || in_array('*', $ignore);

        return $utility
            ->mapKeys(fn ($k, $v): array => [$v['name'] => $v['extra'][$forPackage] ?? []])
            ->filter(fn ($v): bool => count($v) > 0)
            ->filter(fn ($value, $key): bool => !$shouldIgnoreAll && !in_array($key, $ignore))
            ->value();
    }

    /**
     * Get collection of installed packages
     *
     * @return array<int, array<string, mixed>>
     */
    public function installedPackages(): array
    {
        $packages = [];

        if (($utility = new FileUtility($this->vendorPath() . '/composer/installed.json'))->exists()) {
            $content = $utility->content();

            if (is_string($content)) {
                $installed = json_decode($content, true);
                $packages = $installed['packages'] ?? [];
            }
        }

        return $packages;
    }

    /**
     * Get the absolute location of package
     */
    public function locate(string $packageName): string
    {
        $package = $this->findPackage($packageName);

        return str_replace('../', $this->vendorPath() . '/', $package['install-path']);
    }

    /**
     * @return array<string, mixed>
     */
    public function packageExtra(string $packageName): array
    {
        $package = $this->findPackage($packageName);

        return $package['extra'] ?? [];
    }

    /**
     * @return array<string, mixed>
     */
    public function packageMetaForService(string $packageName, string $serviceName): array
    {
        $package = $this->findPackage($packageName);

        return $package['extra'][$serviceName] ?? [];
    }

    /**
     * Get path to composer vendor directory
     */
    public function vendorPath(): string
    {
        return $this->basePath . '/' . $this->vendorDirectory;
    }

    /**
     * @return array<string, mixed>
     */
    protected function findPackage(string $packageName): array
    {
        $packages = new BagUtility($this->installedPackages())->mapKeys(fn ($k, $v): array => [$v['name'] => $v])->value();

        if (!isset($packages[$packageName])) {
            throw new InvalidArgumentException($packageName . ' is not a known package');
        }

        return $packages[$packageName];
    }

    /**
     * Get names of packages to ignore
     *
     * @return array<int, string>
     */
    protected function ignore(string $forPackage): array
    {
        $ignore = [];

        if (($utility = new FileUtility($this->basePath . '/composer.json'))->exists()) {
            $content = $utility->content();

            if (is_string($content)) {
                $ignore = json_decode($content, true)['extra'][$forPackage]['avoid'] ?? [];
            }
        }

        return new BagUtility($ignore)->filter()->value();
    }
}
