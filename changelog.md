# Changelog
The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.1.0/) and uses [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]
Unreleased features and fixes can be viewed on GitHub. To do this, click on [Unreleased].

## [0.10.0] - 2023-11-23

### Added
- add removeConfigurationAll() method to remove all module settings (#29)
- add autoloader to export (#30)
- add changelog.md

### Fixed
- only check for updates when we are in the admin module area.

### Changed
- add type hints where possible. Must be compatible with php 7.4
- remove global var $rthModifiedStdModuleMessages and use StdModule:messages instead
- improve README.md
- adjust visibility of the methods of the StdModule class. Check the majority of all modules so that no braking changes occur.

### Removed
- modified compatibility 2.0.3.0, 2.0.4.0, 2.0.4.1, 2.0.4.2

## [0.9.0] - 2023-02-28
### Added
- set admin access for admin with id other than 1 (#24)
- add removeConfiguration() method (#17)
- add CaseConverter (#20)
- add module enabled tests (#26)
- add UnitTest for CaseConverter

### Fixed
- constant already defined (#28)
- array key warnings/notices
- screaming to camel conversion (#21)

### Changed
- set getConfig default return to false (#19)

## [0.8.1] - 2023-02-06
### Fixed
- shipping modul update #14
- abort update check if user is not an admin (#15)

### Changed
- make method screamingCaseToLispCase, screamingCaseToCamelCase public

## [0.8.0] - 2023-01-17
### Added
- add StdController

## [0.7.0] - 2023-01-17
### Added
- add helper functions rth_is_module_enabled() and rth_is_module_disabled()
- add module status methods (#4)
- add module link to update prompt (#6)
- check for update compare version (#5)
- remove need to specify $modulePrefix (#2)

### Fixed
- undefined array key "moduleaction" (#13)
- prevent multiple adds of same key

### Changed
- add version prefix v (vX.X.X) (#10)

## [0.6.3] - 2022-12-27
### Added
- add 2.0.7.0, 2.0.7.1, 2.0.7.2 to modified compatibility

## [0.6.2] - 2021-07-05
### Added
- add 2.0.6.0 to modified compatibility

### Fixed
- spelling of method addConfigurationDropDown and addConfigurationDropDownByStaticFunction

### Changed
- improve docs and examples

## [0.6.1] - 2021-03-26
### Fixed
- remove version config during module uninstall
- errors with checkForUpdate()
- only check for updated if module is installed
- do not check for update is $_GET['action'] set
- do not show actions if module is not installed

## [0.6.0] - 2021-03-01
### Added
- add methods invokeUpdate() and updateSteps()
- add new method checkForUpdate()
- set version to db configuration during installation
- add version to title
- add new method renameConfiguration()
- add new method addAction()
- add new method addMessage()
- add new methods getVersion(), setVersion()

### Fixed
- only do choose the correct module actions

## [0.5.0] - 2021-02-25
### Added
- add methods addConfigurationOrderStatus(), addCofigurationDropDown() and addCofigurationDropDownByStaticFunction()

## [0.4.1] - 2021-02-23
### Fixed
- can not find RuntimeException
- check if a constant was defined to avoid a warning and added a default parameter (#1)

## [0.4.0] - 2020-10-13
### Added
- throw an exception if an undefined variable is accessed.
- add new class Configuration
- add a short description in moduleinfo.json
- add README.md

### Fixed
- use proper module code

## [0.3.0] - 2020-07-13
### Added
- add modifiedCompatibility 2.0.5.0 and 2.0.5.1

## [0.2.0] - 2020-05-19
### Added
- add require and autoload to moduleinfo.json

## [0.1.0] - 2020-01-27
### Changed
- use auto-version in moduleinfo.json


## [0.0.1] - 2019-01-15
### Added
- initial commit

[Unreleased]: https://github.com/RobinTheHood/modified-std-module/compare/0.9.0...HEAD
[0.10.0]: https://github.com/RobinTheHood/modified-std-module/compare/0.9.0...0.10.0
[0.9.0]: https://github.com/RobinTheHood/modified-std-module/compare/0.8.1...0.9.0
[0.8.1]: https://github.com/RobinTheHood/modified-std-module/compare/0.8.0...0.8.1
[0.8.0]: https://github.com/RobinTheHood/modified-std-module/compare/0.7.0...0.8.0
[0.7.0]: https://github.com/RobinTheHood/modified-std-module/compare/0.6.3...0.7.0
[0.6.3]: https://github.com/RobinTheHood/modified-std-module/compare/0.6.2...0.6.3
[0.6.2]: https://github.com/RobinTheHood/modified-std-module/compare/0.6.1...0.6.2
[0.6.1]: https://github.com/RobinTheHood/modified-std-module/compare/0.6.0...0.6.1
[0.6.0]: https://github.com/RobinTheHood/modified-std-module/compare/0.5.0...0.6.0
[0.5.0]: https://github.com/RobinTheHood/modified-std-module/compare/0.4.1...0.5.0
[0.4.1]: https://github.com/RobinTheHood/modified-std-module/compare/0.4.0...0.4.1
[0.4.0]: https://github.com/RobinTheHood/modified-std-module/compare/0.3.0...0.4.0
[0.3.0]: https://github.com/RobinTheHood/modified-std-module/compare/0.2.0...0.3.0
[0.2.0]: https://github.com/RobinTheHood/modified-std-module/compare/0.1.0...0.2.0
[0.1.0]: https://github.com/RobinTheHood/modified-std-module/compare/0.0.1...0.1.0
[0.0.1]: https://github.com/RobinTheHood/modified-std-module/releases/tag/0.0.1
