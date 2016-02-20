# JBZoo Data  [![Build Status](https://travis-ci.org/JBZoo/Data.svg?branch=master)](https://travis-ci.org/JBZoo/Data) [![Coverage Status](https://coveralls.io/repos/JBZoo/Data/badge.svg?branch=master&service=github)](https://coveralls.io/github/JBZoo/Data?branch=master)

#### Extended implementation of ArrayObject

[![License](https://poser.pugx.org/JBZoo/Data/license)](https://packagist.org/packages/JBZoo/Data)
[![Latest Stable Version](https://poser.pugx.org/JBZoo/Data/v/stable)](https://packagist.org/packages/JBZoo/Data) [![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/JBZoo/Data/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/JBZoo/Data/?branch=master)

## Install
```sh
composer require jbzoo/data:"1.x-dev"  # Last version
composer require jbzoo/data            # Stable version
```


## Compare useful for every day
| Action | JBZoo/Data (ArrayObject)  | Simple PHP Array |
| ------------- | ------------- | ------------- |
| Create  | `$d = new Data($someData)`  | `$ar = [/* ... */];`
| Supported formats | Array, Object, ArrayObject, JSON, INI, Yml, File | Array
| Get value or default  | `$d->get('key', 42)`  | `array_key_exists('k', $ar) ? $ar['k'] : 42`
| Get undefined #1  | `$d->get('undefined')` | `@$ar['undefined']` (@ is bad)
| Get undefined #2 | `$d->find('undefined')` | `isset($ar['und']) ? $ar['und'] : null`
| Get undefined #3  | `@d['undefined']` (@ is bad) | -
| Like array  | `$d['key']`  | `$ar['key']`
| Like object #1 | `$d->key` | -
| Like object #2 | `$d->get('key')` | -
| Like object #3 | `$d->find('key')` | -
| Like object #4 | `$d->offsetGet('key')` | -
| Isset #1 | `isset($d->key)` | `isset($ar['key'])`
| Isset #2 | `isset($d['key'])` | `array_key_exists('key', $ar)`
| Isset #3 | `$d->has('key')` | -
| Nested key  #1 | `$d->find('inner.inner.prop', $default)` | `$ar['inner']['inner']['prop']`
| Nested key  #2 | `$d->inner['inner']['prop']` | -
| Nested key  #3 | `$d['inner']['inner']['prop']` | -
| Export to Serialized | `echo (new Data([/* ... */]))` | `echo serialize([/* ... */])`
| Export to JSON | `echo (new JSON([/* ... */]))` | `echo json_encode([/* ... */])`
| Export to Yml | `echo (new Yml ([/* ... */]))` | -
| Export to ini | `echo (new Ini([/* ... */]))` | -
| Export to PHP Code | `echo (new PHPArray ([/* ... */]))` | -
| Pretty JSON format | **+** | **-**
| Load data from file | **+** | **-**


## Overhead on PHP 5.6.x
All benchmark tests are executing without xdebug and with big random array and 10 000 iterations.
For more details [see travis log](https://travis-ci.org/JBZoo/Data/jobs/110570934)

| Action | JBZoo/Data  | ArrayObject | Simple PHP Array |
| ------------- | ------------- |------------- | ------------- |
| Create - time | 100% | 24% | -
| Create - memory | - | - | 8%
| Get by key - time | 21% | - | 5%
| Get by key - memory | - | - | 906%
| Find nested defined var - time | 41% | 4% | -
| Find nested defined var - memory | 440% | - | 940%
| Find nested undefined var - time | 22% | 4% | -
| Find nested undefined var - memory | - | - | -
| Average time for **10 000 iterations!** | 68ms (+44%) | 51ms (+9%) | 47ms

## Overhead on PHP 7.0.x
All benchmark tests are executing without xdebug and with big random array and 100 000 iterations.
For more details [see travis log](https://travis-ci.org/JBZoo/Data/jobs/110570935)

| Action | JBZoo/Data  | ArrayObject | Simple PHP Array |
| ------------- | ------------- |------------- | ------------- |
| Create - time | 373% | 161% | -
| Create - memory | - | - | -
| Get by key - time | 90% | 2% | -
| Get by key - memory | - | - | -
| Find nested defined var - time | - | 28% | 15%
| Find nested defined var - memory | - | - | -
| Find nested undefined var - time | - | 22% | 6%
| Find nested undefined var - memory | - | - | -
| Average time for **100 000 iterations!** | 77ms (+54%) | 65ms (+30%) | 50ms


## Unit tests and check code style
```sh
composer update-all
composer test
```


## License

MIT
