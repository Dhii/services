# Change log
All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](http://keepachangelog.com/)
and this project adheres to [Semantic Versioning](http://semver.org/).

## [[*next-version*]] - YYYY-MM-DD

## [0.1.1-alpha3] - 2023-02-01
### Added
- The `Constructor`, `FuncService`, `ServiceList`, `StringService`, `Extension`, and `ArrayExtension` services now
  accept other service instances as dependencies, in addition to strings (#14).

### Deprecated
- The `ResolveKeysCabableTrait::resolveKeys()` method is now deprecated. Clients should use the `resolveDeps()` method
  instead (#14).

## [0.1.1-alpha2] - 2022-01-05
### Fixed
- `Service::fromFile()` used to fail when trying an existing readable file.

## [0.1.1-alpha1] - 2022-01-05
### Added
- `Service::fromFile()`, which helps split configuration into multiple files (#9).

## [0.1.0] - 2022-01-05
Stable release.

## [0.1.0-alpha2] - 2021-03-16
### Remove
- Support for PHP < 7.1 (#3).

### Add
- Support for PHP 8 (#3).
- Extract `resolveKeys()` to trait (#3).

## [0.1.0-alpha1] - 2021-03-15
Initial version.
