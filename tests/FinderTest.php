<?php

declare(strict_types=1);

namespace Tests;

use Myerscode\PackageDiscovery\Exceptions\PackageNotFoundException;
use Myerscode\PackageDiscovery\Finder;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use InvalidArgumentException;
use Iterator;

final class FinderTest extends TestCase
{
    public static function discoverDataProvider(): Iterator
    {
        yield [
            'test_one',
            'myerscode',
            1,
        ];
        yield [
            'test_two',
            'corgi',
            0,
        ];
        yield [
            'test_three',
            'myerscode',
            0,
        ];
        yield [
            'test_four',
            'myerscode',
            0,
        ];
        yield [
            'test_five',
            'myerscode',
            0,
        ];
    }

    public function testCanCheckIfPackageIsInstalled(): void
    {
        $basePath = __DIR__.'/Resources/test_locate';
        $finder = new Finder($basePath);

        $this->assertTrue($finder->has('myerscode/test-package'));
        $this->assertFalse($finder->has('myerscode/not-a-package'));
    }

    public function testCanDiscoverAllPackagesWithExtras(): void
    {
        $basePath = __DIR__.'/Resources/test_locate';
        $finder = new Finder($basePath);

        $all = $finder->discoverAll();

        $this->assertIsArray($all);
        $this->assertArrayHasKey('myerscode/test-package', $all);
        $this->assertArrayNotHasKey('myerscode/utilities-bags', $all);
    }

    public function testCanDiscoverMultipleNamespacesAtOnce(): void
    {
        $basePath = __DIR__.'/Resources/test_locate';
        $finder = new Finder($basePath);

        $discovered = $finder->discover(['myerscode', 'corgi']);

        $this->assertArrayHasKey('myerscode/test-package', $discovered);
        $this->assertCount(1, $discovered);
    }

    public function testCanDiscoverPackagesByType(): void
    {
        $basePath = __DIR__.'/Resources/test_one';
        $finder = new Finder($basePath);

        $plugins = $finder->discoverByType('composer-plugin', 'myerscode');
        $this->assertArrayHasKey('myerscode/test-package', $plugins);

        $libraries = $finder->discoverByType('library', 'myerscode');
        $this->assertSame([], $libraries);
    }

    public function testCanGePackageExtras(): void
    {
        $basePath = __DIR__.'/Resources/test_locate';
        $finder = new Finder($basePath);

        $meta = $finder->packageExtra('myerscode/test-package');

        $this->assertSame([
            'myerscode' => [
                'corgis' => ['Gerald', 'Rupert'],
                'providers' => [
                    'Myerscode\\Testing\\TestingServiceProvider',
                ],
            ],
            'corgi' => [
                'names' => ['Gerald', 'Rupert'],
            ],
        ], $meta);
    }

    public function testCanGetInstalledPackageNames(): void
    {
        $basePath = __DIR__.'/Resources/test_locate';
        $finder = new Finder($basePath);

        $names = $finder->installedPackageNames();

        $this->assertIsArray($names);
        $this->assertContains('myerscode/test-package', $names);
        $this->assertContains('myerscode/utilities-bags', $names);
    }

    public function testCanGetMetaForPackageUsingMetaNamespace(): void
    {
        $basePath = __DIR__.'/Resources/test_locate';
        $finder = new Finder($basePath);

        $meta = $finder->packageMetaForService('myerscode/test-package', 'myerscode');

        $this->assertSame([
            'corgis' => ['Gerald', 'Rupert'],
            'providers' => [
                'Myerscode\\Testing\\TestingServiceProvider',
            ],
        ], $meta);

        $meta = $finder->packageMetaForService('myerscode/test-package', 'corgi');

        $this->assertSame(['names' => ['Gerald', 'Rupert']], $meta);
    }

    public function testCanLocatePackage(): void
    {
        $basePath = __DIR__.'/Resources/test_locate';
        $finder = new Finder($basePath);

        $location = $finder->locate('myerscode/test-package');

        $expected = realpath(__DIR__.'/Resources/test_locate/vendor/myerscode/test-package');
        $this->assertSame($expected, $location);
    }

    public function testCanSeeInstalledPackages(): void
    {
        $basePath = __DIR__.'/../';
        $finder = new Finder($basePath);
        $installed = $finder->installedPackages();
        $this->assertIsArray($installed);
        $this->assertGreaterThan(0, count($installed));
    }

    public function testDiscoverAllReturnsEmptyWhenNoPackagesHaveExtras(): void
    {
        $basePath = __DIR__.'/Resources';
        $finder = new Finder($basePath);

        $this->assertSame([], $finder->discoverAll());
    }

    public function testDiscoverByTypeReturnsEmptyForUnknownType(): void
    {
        $basePath = __DIR__.'/Resources/test_one';
        $finder = new Finder($basePath);

        $this->assertSame([], $finder->discoverByType('unknown-type', 'myerscode'));
    }

    public function testDiscoverWithArrayReturnsEmptyForUnknownNamespaces(): void
    {
        $basePath = __DIR__.'/Resources/test_locate';
        $finder = new Finder($basePath);

        $this->assertSame([], $finder->discover(['unknown-ns']));
    }


    #[DataProvider('discoverDataProvider')]
    public function testFindsDiscoverablePackages(string $location, string $discover, int $found): void
    {
        $basePath = __DIR__.'/Resources/'.$location;

        $finder = new Finder($basePath);

        $discovered = $finder->discover($discover);

        $this->assertCount($found, $discovered);
    }

    public function testHandlesMissingInstallFile(): void
    {
        $basePath = __DIR__.'/Resources';
        $finder = new Finder($basePath);
        $installed = $finder->installedPackages();
        $this->assertIsArray($installed);
        $this->assertCount(0, $installed);
    }

    public function testInstalledPackagesAreCached(): void
    {
        $basePath = __DIR__.'/../';
        $finder = new Finder($basePath);

        $first = $finder->installedPackages();
        $second = $finder->installedPackages();

        $this->assertSame($first, $second);
    }

    public function testPackageNotFoundExceptionExtendsInvalidArgumentException(): void
    {
        $basePath = __DIR__.'/Resources/test_locate';
        $finder = new Finder($basePath);

        try {
            $finder->locate('myerscode/does-not-exists-package');
            $this->fail('Expected exception was not thrown');
        } catch (InvalidArgumentException $e) {
            $this->assertInstanceOf(PackageNotFoundException::class, $e);
        }
    }

    public function testThrowsExceptionWhenCannotLocatePackage(): void
    {
        $basePath = __DIR__.'/Resources/test_locate';
        $finder = new Finder($basePath);
        $packageName = 'myerscode/does-not-exists-package';

        $this->expectException(PackageNotFoundException::class);
        $this->expectExceptionMessage($packageName . ' is not a known package');
        $finder->locate($packageName);
    }

    public function testThrowsExceptionWhenPackagePathCannotBeResolved(): void
    {
        $basePath = __DIR__.'/Resources/test_locate';
        $finder = new Finder($basePath);

        $this->expectException(PackageNotFoundException::class);
        $this->expectExceptionMessage('Could not resolve path for package: myerscode/ghost-package');
        $finder->locate('myerscode/ghost-package');
    }
}
