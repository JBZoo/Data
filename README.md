# JBZoo Data  [![Build Status](https://travis-ci.org/JBZoo/Data.svg?branch=master)](https://travis-ci.org/JBZoo/Data) [![Coverage Status](https://coveralls.io/repos/JBZoo/Data/badge.svg?branch=master&service=github)](https://coveralls.io/github/JBZoo/Data?branch=master)

Extended implementation of [ArrayObject](http://php.net/manual/en/class.arrayobject.php).

Really useful objects for any config in your system (write, read, store, change, validate, convert to other format and etc).

[![License](https://poser.pugx.org/JBZoo/Data/license)](https://packagist.org/packages/JBZoo/Data)
[![Latest Stable Version](https://poser.pugx.org/JBZoo/Data/v/stable)](https://packagist.org/packages/JBZoo/Data) [![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/JBZoo/Data/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/JBZoo/Data/?branch=master)

## Install
```sh
composer require jbzoo/data            # Stable version
```


## Comparing the useful for every day
| Action | JBZoo/Data (ArrayObject)  | Simple PHP Array |
| ------------- | ------------- | ------------- |
| Create  | `$d = new Data($someData)`  | `$ar = [/* ... */];`
| Supported formats | Array, Object, ArrayObject, JSON, INI, Yml  | Array only
| Load form file | with array, ini, yml, json, serialized | -
| Get value or default  | `$d->get('key', 42)`  | `array_key_exists('k', $ar) ? $ar['k'] : 42`
| Get undefined #1  | `$d->get('undefined')` | `@$ar['undefined']` (@ is bad idea!)
| Get undefined #2 | `$d->find('undefined')` | `isset($ar['und']) ? $ar['und'] : null`
| Get undefined #3  | `$d->undefined === null` (no any notice) | -
| Get undefined #4  | `$d['undefined'] === null` (no any notice) | -
| Get undefined #5  | `$d['undef']['undef'] === null` (no any notice) | -
| Compare #1  | `$d->get('key') === $someVar` | $ar['key'] === $someVar
| Compare #2  | `$d->is('key', $someVar)` | -
| Compare #3  | `$d->is('key', $someVar, true)` (strict) | -
| Like array  | `$d['key']`  | `$ar['key']`
| Like object #1 | `$d->key` | -
| Like object #2 | `$d->get('key')` | -
| Like object #3 | `$d->find('key')` | -
| Like object #4 | `$d->offsetGet('key')` | -
| Isset #1 | `isset($d['key'])` | `isset($ar['key'])`
| Isset #2 | `isset($d->key)` | `array_key_exists('key', $ar)`
| Isset #3 | `$d->has('key')` | -
| Nested key  #1 | `$d->find('inner.inner.prop', $default)` | `$ar['inner']['inner']['prop']`
| Nested key  #2 | `$d->inner['inner']['prop']` | -
| Nested key  #3 | `$d['inner']['inner']['prop']` | -
| Export to Serialized | `echo (new Data([/* ... */]))` | `echo serialize([/* ... */])`
| Export to JSON | `echo (new JSON([/* ... */]))` (readable) | `echo json_encode([/* ... */])`
| Export to Yml | `echo (new Yml ([/* ... */]))` (readable) | -
| Export to Ini | `echo (new Ini([/* ... */]))` (readable) | -
| Export to PHP Code | `echo (new PHPArray ([/* ... */]))` (readable) | -
| Pretty JSON | **+** | -
| Filters | **+** | -
| Search | **+** | -
| Flatten Recursive | **+** | -

## Usage
#### Methods to create config object
```php
require_once './vendor/autoload.php'; // composer autoload.php

// Get needed classes
namespace JBZoo\Data\Data; // And others

// Create
$config     = new Data([/* Assoc Array */]);       // Any PHP-array or simple object, serialized data
$configIni  = new Ini('./configs/some.ini');       // Load config from ini file (or string, or simple array)
$configYml  = new Yml('./configs/some.yml');       // Yml (or string, or simple array). Parsed with Symfony/Yaml Component.
$configJSON = new JSON('./configs/some.json');     // JSON File (or string, or simple array)
$configPHP  = new PHPArray('./configs/some.php');  // PHP-file that must return array

// Read
$config->get('key', 42);                   // Check exists and get parameter by key or return default value
$config['key'];                            // Like array
$config->key;                              // Like object
$config->find('very.deep.config.key', 42); // Check and get $config['very']['deep']['config']['key'] or return default

// Write
$config->set('key', 42);    // Method
$config['key'] = 42;        // Like array
$config->key = 42;          // Like object

// Isset
$config->has('key');        // Method
isset($config['key']);      // Like array
isset($config->key);        // Like object

// Unset
$config->remove('key');     // Method
unset($config['key']);      // Like array
unset($config->key);        // Like object
```


#### Filter values (required JBZoo/Utils)

List of filters - [JBZoo/Utils/Filter](https://github.com/JBZoo/Utils/blob/master/src/Filter.php)
 * `bool` -  Converts many english words that equate to true or false to boolean.
 * `int` - Smart filter for integer
 * `float` - Smart filter for float
 * `digits` - Only 0..9
 * `alpha` - Only a..z
 * `alphanum` - Only 0..9 and a..z
 * `base64` - Return only chars for base64
 * `path` - Some path
 * `trim` - Extend trim
 * `arr` - Array filter
 * `cmd` - Cleanup for system command
 * `email` - Email or null
 * `strip` - Strip tags
 * `alias` - Sluggify
 * `low` - String to lower (check mbstring)
 * `up` - String to upper (check mbstring)
 * `clean` - Safe string
 * `html` - HTML escaping
 * `xml` - XML escaping
 * `esc` - Escape for UTF-8
 * `function($value) { return $value; }` - Your custom callback function

```php
$config->get('key', 42, 'int');     // Smart converting to integer
$config->find('key', 42, 'float');  // To float
$config->find('no', 'yes', 'bool'); // Smart converting popular word to boolean value

$config->get('key', 42, 'strip, trim'); // Chain of filters

// Custom handler
$config->get('key', 42, function($value) {
    return (float)str_replace(',', '.', $value);
});
```


#### Utility methods
```php
$config->search($needle);       // Find a value also in nested arrays/objects
$config->flattenRecursive();    // Return flattened array copy. Keys are <b>NOT</b> preserved.
```

#### Save to pretty format
```php
echo $config;
$result = '' . $config;
$result = (string)$config;
$result = $config->write();
$result = $config->__toString();
```

Example of serializing the `JSON` object
```json
{
    "empty": "",
    "zero": "0",
    "string": " ",
    "tag": "<a href=\"http:\/\/google.com\">Google.com<\/a>",
    "array1": {
        "0": "1",
        "1": "2"
    },
    "section": {
        "array2": {
            "0": "1",
            "12": "2",
            "3": "3"
        }
    },
    "section.nested": {
        "array3": {
            "00": "0",
            "01": "1"
        }
    }
}
```

Example of serializing the `PHPArray` object
```php
<?php

return array(
    'empty' => '',
    'zero' => '0',
    'string' => ' ',
    'tag' => '<a href="http://google.com">Google.com</a>',
    'array1' => array(
        0 => '1',
        1 => '2',
    ),
    'section' => array(
        'array2' => array(
            0 => '1',
            12 => '2',
            3 => '3',
        ),
    ),
    'section.nested' => array(
        'array3' => array(
            '00' => '0',
            '01' => '1',
        ),
    ),
);
```

Example of serializing the `Yml` object
```yml
empty: ''
zero: '0'
string: ' '
tag: '<a href="http://google.com">Google.com</a>'
array1:
    - '1'
    - '2'
section:
    array2: { 0: '1', 12: '2', 3: '3' }
section.nested:
    array3: ['0', '1']
```

Example of serializing the `Ini` object
```ini
empty = ""
zero = "0"
string = " "
tag = "<a href=\"http://google.com\">Google.com</a>"
array1[0] = "1"
array1[1] = "2"

[section]
array2[0] = "1"
array2[12] = "2"
array2[3] = "3"

[section.nested]
array3[00] = "0"
array3[01] = "1"
```

Example of serializing the `Data` object
```
a:7:{s:5:"empty";s:0:"";s:4:"zero";s:1:"0";s:6:"string";s:1:" ";s:3:"tag";s:42:"<a href="http://google.com">Google.com</a>";s:6:"array1";a:2:{i:0;s:1:"1";i:1;s:1:"2";}s:7:"section";a:1:{s:6:"array2";a:3:{i:0;s:1:"1";i:12;s:1:"2";i:3;s:1:"3";}}s:14:"section.nested";a:1:{s:6:"array3";a:2:{s:2:"00";s:1:"0";s:2:"01";s:1:"1";}}}
```

## Summary benchmark info (execution time) PHP v7.4
All benchmark tests are executing without xdebug and with a huge random array and 100.000 iterations.

Benchmark tests based on the tool [phpbench/phpbench](https://github.com/phpbench/phpbench). See details [here](tests/phpbench).   

Please, pay attention - `1μs = 1/1.000.000 of second!`

**benchmark: CreateObject**
subject | groups | its | revs | mean | stdev | rstdev | mem_real | diff
 --- | --- | --- | --- | --- | --- | --- | --- | --- 
benchArrayObjectOrig | Native,ArrayObject | 3 | 100000 | 7.30μs | 0.01μs | 0.18% | 8,388,608b | 1.00x
benchArrayObjectExtOrig | Native,ArrayObject,Extended | 3 | 100000 | 7.43μs | 0.05μs | 0.66% | 8,388,608b | 1.02x
benchJson | JSON | 3 | 100000 | 7.55μs | 0.01μs | 0.15% | 8,388,608b | 1.03x
benchIni | Ini | 3 | 100000 | 7.55μs | 0.01μs | 0.15% | 8,388,608b | 1.03x
benchData | Data | 3 | 100000 | 7.57μs | 0.03μs | 0.41% | 8,388,608b | 1.04x
benchIniFunc | Ini,Func | 3 | 100000 | 7.62μs | 0.01μs | 0.10% | 8,388,608b | 1.04x
benchDataFunc | Data,Func | 3 | 100000 | 7.63μs | 0.01μs | 0.19% | 8,388,608b | 1.05x
benchYml | Yml | 3 | 100000 | 7.63μs | 0.10μs | 1.36% | 8,388,608b | 1.05x
benchJsonFunc | JSON,Func | 3 | 100000 | 7.64μs | 0.01μs | 0.11% | 8,388,608b | 1.05x
benchPhpArray | PhpArray | 3 | 100000 | 7.65μs | 0.03μs | 0.44% | 8,388,608b | 1.05x
benchYmlFunc | Yml,Func | 3 | 100000 | 7.70μs | 0.05μs | 0.60% | 8,388,608b | 1.05x
benchPhpArrayFunc | PhpArray,Func | 3 | 100000 | 7.75μs | 0.06μs | 0.72% | 8,388,608b | 1.06x


**benchmark: GetUndefinedValue**
subject | groups | its | revs | mean | stdev | rstdev | mem_real | diff
 --- | --- | --- | --- | --- | --- | --- | --- | --- 
benchArrayIsset | Native,Array,Undefined | 3 | 1000000 | 0.04μs | 0.00μs | 1.48% | 8,388,608b | 1.00x
benchDataOffsetGet | Data,Undefined | 3 | 1000000 | 0.11μs | 0.00μs | 0.41% | 8,388,608b | 2.88x
benchDataGet | Data,Undefined | 3 | 1000000 | 0.14μs | 0.00μs | 0.39% | 8,388,608b | 3.56x
benchDataArray | Data,Undefined | 3 | 1000000 | 0.14μs | 0.00μs | 0.08% | 8,388,608b | 3.72x
benchDataArrow | Data,Undefined | 3 | 1000000 | 0.15μs | 0.00μs | 0.34% | 8,388,608b | 3.86x
benchArrayRegularMuted | Native,Array,Undefined | 3 | 1000000 | 0.19μs | 0.00μs | 0.04% | 8,388,608b | 4.99x
benchDataFind | Data,Undefined | 3 | 1000000 | 0.37μs | 0.00μs | 0.11% | 8,388,608b | 9.69x
benchDataFindInner | Data,Undefined | 3 | 1000000 | 0.41μs | 0.00μs | 0.14% | 8,388,608b | 10.86x


**benchmark: GetValue**
subject | groups | its | revs | mean | stdev | rstdev | mem_real | diff
 --- | --- | --- | --- | --- | --- | --- | --- | --- 
benchArrayRegular | Native,Array | 3 | 1000000 | 0.04μs | 0.00μs | 5.02% | 8,388,608b | 1.00x
benchArrayRegularMuted | Native,Array | 3 | 1000000 | 0.04μs | 0.00μs | 1.40% | 8,388,608b | 1.06x
benchArrayIsset | Native,Array | 3 | 1000000 | 0.04μs | 0.00μs | 2.04% | 8,388,608b | 1.07x
benchArrayObjectArray | Native,ArrayObject | 3 | 1000000 | 0.05μs | 0.00μs | 1.07% | 8,388,608b | 1.14x
benchArrayObjectArrayExt | Native,ArrayObject,Extended | 3 | 1000000 | 0.05μs | 0.00μs | 0.24% | 8,388,608b | 1.19x
benchArrayObjectOffsetGet | Native,ArrayObject | 3 | 1000000 | 0.07μs | 0.00μs | 1.35% | 8,388,608b | 1.77x
benchArrayObjectExtOffsetGet | Native,ArrayObject,Extended | 3 | 1000000 | 0.08μs | 0.00μs | 0.23% | 8,388,608b | 1.86x
benchDataOffsetGet | Data | 3 | 1000000 | 0.16μs | 0.00μs | 0.28% | 8,388,608b | 4.01x
benchDataArray | Data | 3 | 1000000 | 0.20μs | 0.00μs | 0.17% | 8,388,608b | 4.96x
benchDataArrow | Data | 3 | 1000000 | 0.21μs | 0.00μs | 0.21% | 8,388,608b | 5.07x
benchDataGet | Data | 3 | 1000000 | 0.28μs | 0.00μs | 0.21% | 8,388,608b | 6.95x
benchDataFind | Data | 3 | 1000000 | 0.35μs | 0.00μs | 0.65% | 8,388,608b | 8.52x

**benchmark: GetValueInner**
subject | groups | its | revs | mean | stdev | rstdev | mem_real | diff
 --- | --- | --- | --- | --- | --- | --- | --- | --- 
benchArrayRegular | Native,Array | 3 | 1000000 | 0.05μs | 0.00μs | 0.23% | 8,388,608b | 1.00x
benchArrayRegularMuted | Native,Array | 3 | 1000000 | 0.06μs | 0.00μs | 0.86% | 8,388,608b | 1.06x
benchArrayIsset | Native,Array | 3 | 1000000 | 0.06μs | 0.00μs | 0.27% | 8,388,608b | 1.08x
benchArrayObjectArrayExt | Native,ArrayObject,Extended | 3 | 1000000 | 0.06μs | 0.00μs | 0.76% | 8,388,608b | 1.14x
benchArrayObjectArray | Native,ArrayObject | 3 | 1000000 | 0.07μs | 0.00μs | 1.39% | 8,388,608b | 1.22x
benchDataFind | Data | 3 | 1000000 | 0.81μs | 0.01μs | 1.06% | 8,388,608b | 15.22x


## Unit tests and check code style
```sh
make update
make test-all
```


## License

MIT
