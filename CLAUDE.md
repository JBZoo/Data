# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

JBZoo Data is a PHP library that provides an extended version of ArrayObject for working with data arrays and various formats (JSON, YAML, INI, PHP arrays). It offers a fluent API for data manipulation with built-in filtering, nested access, and format conversion capabilities.

## Core Architecture

### Abstract Base Class Pattern
- `AbstractData` - Base class extending PHP's ArrayObject with common functionality
- `AliasesTrait` - Provides multiple access methods (array, object, method-based)
- Concrete implementations: `Data`, `JSON`, `Yml`, `Ini`, `PhpArray`

### Function-Based Factory Pattern
The `src/functions.php` file provides factory functions for creating instances:
- `data()` - Creates Data objects
- `json()` - Creates JSON objects
- `yml()` - Creates Yml objects
- `ini()` - Creates Ini objects
- `phpArray()` - Creates PhpArray objects

### Data Access Patterns
Multiple ways to access data with graceful undefined handling:
- Array access: `$data['key']`
- Object access: `$data->key`
- Method access: `$data->get('key', $default)`
- Nested access: `$data->find('deep.nested.key', $default)`

### Format Support
Each class handles specific data formats:
- `JSON` - JSON strings and files
- `Yml` - YAML format (requires symfony/yaml)
- `Ini` - INI configuration files
- `PhpArray` - PHP files returning arrays
- `Data` - Generic/serialized data

## Common Commands

### Development Setup
```bash
make update          # Install/update all dependencies via Composer
make autoload        # Dump optimized autoloader
```

### Testing
```bash
make test           # Run PHPUnit tests
make test-all       # Run all tests and code style checks at once
make codestyle      # Run all linters and style checks
```

### Individual Quality Assurance Tools
```bash
make test-phpstan        # Static analysis with PHPStan
make test-psalm          # Static analysis with Psalm
make test-phpcs          # PHP CodeSniffer (PSR-12 + compatibility)
make test-phpcsfixer     # PHP-CS-Fixer style check
make test-phpcsfixer-fix # Auto-fix code style issues
make test-phpmd          # PHP Mess Detector
make test-phan          # Phan static analyzer
make test-performance   # Run benchmark tests
```

### Reports and Analysis
```bash
make report-all         # Generate all analysis reports
make report-phpmetrics  # PHP Metrics report
make report-pdepend     # PHP Depend analysis
make report-phploc      # Lines of code statistics
```

## Development Standards

### PHP Requirements
- PHP 8.2+ required
- Strict types enabled (`declare(strict_types=1)`)
- PSR-12 coding standard
- Full type hints required

### Code Patterns
When extending the library:
1. New format classes should extend `AbstractData`
2. Implement `decode()` and `encode()` abstract methods
3. Add factory function to `functions.php`
4. Follow existing naming conventions (`Yml` not `Yaml`, `PhpArray` not `PHPArray`)

### Testing Structure
- Tests in `tests/` directory follow naming pattern `Data{Format}Test.php`
- Benchmark tests in `tests/phpbench/` for performance monitoring
- Test fixtures in `tests/resource/`
- Use `tests/Fixtures.php` for common test data

### Performance Considerations
The library includes comprehensive benchmarks comparing:
- Native PHP arrays vs ArrayObject performance
- Different access methods (array, object, method calls)
- Data retrieval patterns for defined vs undefined values

## Key Dependencies

### Required
- `php: ^8.2`
- `ext-json: *`

### Development/Optional
- `jbzoo/toolbox-dev: ^7.2` - Development tooling
- `jbzoo/utils: ^7.2.2` - Utility functions for filtering
- `symfony/yaml: >=7.3.3` - YAML parsing support

## Test Data and Fixtures

The library uses shared test fixtures across format tests:
- `tests/Fixtures.php` - Common test data arrays
- `tests/resource/` - Sample data files in various formats
- Consistent test data ensures format conversion accuracy

## Filter Integration

When JBZoo/Utils is available, the library supports data filtering:
- `$data->get('key', $default, 'int')` - Type conversion
- `$data->find('key', $default, 'email')` - Email validation
- Chain filters: `'strip,trim'`
- Custom callbacks supported

## Build System Integration

The project uses JBZoo's standardized Makefile system via:
```makefile
ifneq (, $(wildcard ./vendor/jbzoo/codestyle/src/init.Makefile))
    include ./vendor/jbzoo/codestyle/src/init.Makefile
endif
```