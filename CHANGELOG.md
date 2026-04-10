# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/), and this project adheres to [Calendar Versioning](https://calver.org/).

## [2026.0.0] - 2026-04-10
### New Features
- [`6fe9688`](https://github.com/myerscode/package-discovery/commit/6fe96888b203f767e69203c5da1c08e44c29a912) - **exceptions**: add PackageNotFoundException extending InvalidArgumentException *(commit by [@oniice](https://github.com/oniice))*
- [`b013f80`](https://github.com/myerscode/package-discovery/commit/b013f80b18007b7c79d40cc2529dbe99a3de1dce) - **finder**: add has() method for package existence check *(commit by [@oniice](https://github.com/oniice))*
- [`123e032`](https://github.com/myerscode/package-discovery/commit/123e0320174baa774ea0843fb2c1fe5fe3890b57) - **finder**: add installedPackageNames() method *(commit by [@oniice](https://github.com/oniice))*
- [`ee664ab`](https://github.com/myerscode/package-discovery/commit/ee664aba97604145e9b65b677242d3d4ff0062d4) - **finder**: add discoverAll() to find all packages with extra metadata *(commit by [@oniice](https://github.com/oniice))*
- [`1714d06`](https://github.com/myerscode/package-discovery/commit/1714d06c8cb9649c5d09b558126ad4895533f478) - **discover**: support array of namespaces in discover() *(commit by [@oniice](https://github.com/oniice))*
- [`6394ab6`](https://github.com/myerscode/package-discovery/commit/6394ab6474d36b0ed2b5c1211b520fcc1a8aac13) - **finder**: add discoverByType() to filter discovery by Composer package type *(commit by [@oniice](https://github.com/oniice))*

### Bug Fixes
- [`7b3872f`](https://github.com/myerscode/package-discovery/commit/7b3872f45f6b5cbb589b0e58467b471ef7cf6956) - **discover**: remove dead loose comparison in shouldIgnoreAll check *(commit by [@oniice](https://github.com/oniice))*
- [`a42237a`](https://github.com/myerscode/package-discovery/commit/a42237a510a7e8e5262cb24a593010ef3069584f) - **locate**: use realpath() for robust path resolution *(commit by [@oniice](https://github.com/oniice))*

### Performance Improvements
- [`e7dd0a9`](https://github.com/myerscode/package-discovery/commit/e7dd0a963c11cc5de73d0f251e760a4b8b26dd4d) - **discover**: short-circuit when all packages are ignored *(commit by [@oniice](https://github.com/oniice))*
- [`cab8472`](https://github.com/myerscode/package-discovery/commit/cab8472aa16c146d0d7e682bcd0e26142517447e) - **findPackage**: replace BagUtility map with direct loop *(commit by [@oniice](https://github.com/oniice))*
- [`5985e89`](https://github.com/myerscode/package-discovery/commit/5985e89ce60f53afa4ab6a6825ca19569bca94eb) - **installedPackages**: cache result to avoid repeated disk reads *(commit by [@oniice](https://github.com/oniice))*

### Refactors
- [`fe0ff92`](https://github.com/myerscode/package-discovery/commit/fe0ff92a8ecc83ac4106cfd79a25b9384f011a87) - **rector**: modernise codebase with Rector 2.x *(commit by [@oniice](https://github.com/oniice))*


## Unreleased

## [1.0.0](https://github.com/myerscode/package-discovery/releases/tag/1.0.0) - 2022-09-25

- [`d6116be`](https://github.com/myerscode/package-discovery/commit/d6116be6f7cbc56edd4dcab2ba2148ca809348ea) docs: added package meta method usage
- [`46e19d0`](https://github.com/myerscode/package-discovery/commit/46e19d00f264981bc451a602c4de40d4d799af69) feat: added ability to get package meta and extras
- [`acb2931`](https://github.com/myerscode/package-discovery/commit/acb2931561b8bf120d91a13e9ae609509459130e) fix: force adding composer vendor json so tests work
- [`ef3842f`](https://github.com/myerscode/package-discovery/commit/ef3842f5a732d67b27cfea148a132858bfb7979f) tests: github actions now only runs on php 81
- [`a68b3f6`](https://github.com/myerscode/package-discovery/commit/a68b3f66f5d0322c3ce459ad73596a04356af5a1) feat: allow customisation of vendor directory
- [`61811e6`](https://github.com/myerscode/package-discovery/commit/61811e62d20d028ba8d43d29d40af2300dec56a5) feat: added github workflow
- [`1223628`](https://github.com/myerscode/package-discovery/commit/12236283db6c55ce24240f200f5459c9a3014913) fix: corrected path when locating a pacakge to use vendor
- [`d4dd06f`](https://github.com/myerscode/package-discovery/commit/d4dd06f1b9770182c9df38b310fa4576df185d3f) feat: ability to lookup a packages location
- [`dd1d8bf`](https://github.com/myerscode/package-discovery/commit/dd1d8bfe97b3c350bf44d682c681474e66bb37bb) chore: added details on how to consume and publish package
- [`58a1657`](https://github.com/myerscode/package-discovery/commit/58a165734308b2e9db19e4de4ce98ebbd3bef1d6) update: changed field for avoiding package discovery
- [`f27323f`](https://github.com/myerscode/package-discovery/commit/f27323f5a6040d3bd837c675fd82c0df1fb71664) created project
[2026.0.0]: https://github.com/myerscode/package-discovery/compare/2025.0.0...2026.0.0
