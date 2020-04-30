# Changelog
All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

## [1.1.0] - 2020-02-01
### Added
- Compressed geolocation data
- `getMatchedController` and `getMatchedAction`
- Send HTTP Status in error pages
- Docker env

### Changed
- Re-organizing tree.
- Check the output buffer length before clean.
- Use minified versions of `.js` and `.css`.
- Header activates tabs dynamically.
- Fixes redirect.
- Head and Footer on template render.
- Fixes errors related to "headers already sent" due to premature implicit writes to the output buffer.
- Fixed WEB_DIR.
- Changed default `base_url`.
- Fixed font path in captcha. [Souhardya/UBoat#29].
- Error controller has been renamed to Errors [Souhardya/UBoat#43] [Souhardya/UBoat#34] [Souhardya/UBoat#26].

### Removed
- Removed unnecessary css/less/scss files.
- Removed spaces from filenames.
- Remove unused file assets.php.

## [1.0.0] - 2018-08-09
- Initial release.

[Unreleased]: https://github.com/olivierlacan/keep-a-changelog/compare/v1.1...HEAD
[1.1.0]: https://github.com/olivierlacan/keep-a-changelog/compare/v1.0...v1.1
[1.0.0]: https://github.com/olivierlacan/keep-a-changelog/releases/tag/v1.0
