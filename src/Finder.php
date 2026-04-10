<?php

declare(strict_types=1);

namespace Myerscode\PackageDiscovery;

use Myerscode\PackageDiscovery\Exceptions\PackageNotFoundException;
use Myerscode\Utilities\Bags\Utility as BagUtility;
use Myerscode\Utilities\Files\Utility as FileUtility;

class Finder
{
    /** @var array<int, array<string, mixed>>|null */
    private ?array $cachedPackages = null;

    public function __construct(public readonly string $basePath, public readonly string $vendorDirectory = 'vendor')
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

        $shouldIgnoreAll = in_array('*', $ignore);

        if ($shouldIgnoreAll) {
            return [];
        }

        return $utility
            ->mapKeys(fn ($k, $v): array => [$v['name'] => $v['extra'][$forPackage] ?? []])
            ->filter(fn ($v): bool => count($v) > 0)
            ->filter(fn ($value, $key): bool => !in_array($key, $ignore))
            ->value();
    }

    /**
     * Get collection of installed packages
     *
     * @return array<int, array<string, mixed>>
     */
    public function installedPackages(): array
    {
        if ($this->cachedPackages !== null) {
            return $this->cachedPackages;
        }

        $packages = [];

        if (($utility = new FileUtility($this->vendorPath() . '/composer/installed.json'))->exists()) {
            $content = $utility->content();

            if (is_string($content)) {
                $installed = json_decode($content, true);
                $packages = $installed['packages'] ?? [];
            }
        }

        return $this->cachedPackages = $packages;
    }

    /**
     * Get the absolute location of package
     */
    public function locate(string $packageName): string
    {
        $package = $this->findPackage($packageName);

        $resolved = realpath($this->vendorPath() . '/composer/' . $package['install-path']);

        if ($resolved === false) {
            throw new PackageNotFoundException('Could not resolve path for package: ' . $packageName);
        }

        return $resolved;
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
        foreach ($this->installedPackages() as $package) {
            if ($package['name'] === $packageName) {
                return $package;
            }
        }

        throw PackageNotFoundException::forPackage($packageName);
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
