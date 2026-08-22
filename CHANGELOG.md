# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

## [0.1.1]  - 2026-08-22

### Changed
- 


## [0.1.0-beta.1] - 2026-07-27

### Added
- Initial release of `amol/laravel-jsonsafe`
- `JsonSafe` cast implementing `CastsAttributes` for mutable JSON column handling
- `JsonSafeable` class extending `ArrayObject` for convenient array-like access
- JSON Schema validation using `opis/json-schema` via `Validator` class
- `ModelMethodResolver` to resolve `schemaOf{Key}()` methods on Eloquent models
- `JsonRepresentorSerializer` for converting PHP arrays to JSON Schema-compatible format
- Laravel service provider (`LaravelJsonSafeServiceProvider`) using Spatie package tools
- Configuration publishing for `config/jsonsafe.php`
- Support for Laravel 11, 12, and 13
- PHP 8.3+ requirement
- Comprehensive test suite with Pest v4 and Orchestra Testbench
- PHPStan static analysis configuration
- Laravel Pint code style configuration