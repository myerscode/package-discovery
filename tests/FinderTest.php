<?php

namespace Tests;

use Myerscode\PackageDiscovery\Finder;
use PHPUnit\Framework\TestCase;

class FinderTest extends TestCase
{
    public function __discoverData(): array
    {
        return [
            [
                'test_one',
                'myerscode',
                1,
            ],
            [
                'test_two',
                'corgi',
                0,
            ],
            [
                'test_three',
                'myerscode',
                0,
            ],
            [
                'test_four',
                'myerscode',
                0,
            ],
            [
                'test_five',
                'myerscode',
                0,
            ],
        ];
    }

    public function testCanSeeInstalledPackages(): void
    {
        $basePath = __DIR__.'/../';
        $finder = new Finder($basePath);
        $installed = $finder->installedPackages();
        $this->assertIsArray($installed);
        $this->assertGreaterThan(0, count($installed));
    }

    public function testHandlesMissingInstallFile(): void
    {
        $basePath = __DIR__.'/Resources';
        $finder = new Finder($basePath);
        $installed = $finder->installedPackages();
        $this->assertIsArray($installed);
        $this->assertCount(0, $installed);
    }


    /**
     * @dataProvider  __discoverData
     */
    public function testFindsDiscoverablePackages($location, $discover, $found): void
    {
        $basePath = __DIR__.'/Resources/'.$location;

        $finder = new Finder($basePath);

        $discovered = $finder->discover($discover);

        $this->assertCount($found, $discovered);
    }
}
