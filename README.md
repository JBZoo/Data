# JBZoo / Data

[![Build Status](https://travis-ci.org/JBZoo/Data.svg)](https://travis-ci.org/JBZoo/Data)    [![Coverage Status](https://coveralls.io/repos/JBZoo/Data/badge.svg)](https://coveralls.io/github/JBZoo/Data)    [![Psalm Coverage](https://shepherd.dev/github/JBZoo/Data/coverage.svg)](https://shepherd.dev/github/JBZoo/Data)    
[![Stable Version](https://poser.pugx.org/jbzoo/data/version)](https://packagist.org/packages/jbzoo/data)    [![Latest Unstable Version](https://poser.pugx.org/jbzoo/data/v/unstable)](https://packagist.org/packages/jbzoo/data)    [![Dependents](https://poser.pugx.org/jbzoo/data/dependents)](https://packagist.org/packages/jbzoo/data/dependents?order_by=downloads)    [![GitHub Issues](https://img.shields.io/github/issues/jbzoo/data)](https://github.com/JBZoo/Data/issues)    [![Total Downloads](https://poser.pugx.org/jbzoo/data/downloads)](https://packagist.org/packages/jbzoo/data/stats)    [![GitHub License](https://img.shields.io/github/license/jbzoo/data)](https://github.com/JBZoo/Data/blob/master/LICENSE)


An extended version of the [ArrayObject](http://php.net/manual/en/class.arrayobject.php) object for working with system settings or just for working with data arrays.

It provides a short syntax for daily routine, eliminates common mistakes. Allows you to work with various line and file formats - JSON, Yml, Ini, PHP arrays and simple objects.

## Installation
```sh
composer require jbzoo/data
```

## Usage

### Comparison with pure PHP

Action                | JBZoo/Data                                        | Simple PHP Array                            
--------------------- | ------------------------------------------------- | --------------------------------------------
Create                | `$d = data($someData)`                            | `$ar = [/* ... */];`                        
Supported formats     | Array, Object, ArrayObject, JSON, INI, Yml        | Array                                       
Load form file        | *.php, *.ini, *.yml, *.json, serialized           | -                                           
Get value or default  | `$d->get('key', 42)`                              | `array_key_exists('k', $ar) ? $ar['k'] : 42`
Get undefined #1      | `$d->get('undefined')` (no any notice)            | `$ar['undefined'] ?? null`                  
Get undefined #2      | `$d->find('undefined')`                           | `$ar['und'] ??  null`                       
Get undefined #3      | `$d->undefined === null` (no any notice)          | -                                           
Get undefined #4      | `$d['undefined'] === null` (no any notice)        | -                                           
Get undefined #5      | `$d['undef']['undef'] === null` (no any notice)   | -                                           
Comparing #1          | `$d->get('key') === $someVar`                     | `$ar['key'] === $someVar`                   
Comparing #2          | `$d->is('key', $someVar)`                         | -                                           
Comparing #3          | `$d->is('key', $someVar, true)` (strict)          | -                                           
Like array            | `$d['key']`                                       | `$ar['key']`                                
Like object #1        | `$d->key`                                         | -                                           
Like object #2        | `$d->get('key')`                                  | -                                           
Like object #3        | `$d->find('key')`                                 | -                                           
Like object #4        | `$d->offsetGet('key')`                            | -                                           
Isset #1              | `isset($d['key'])`                                | `isset($ar['key'])`                         
Isset #2              | `isset($d->key)`                                  | `array_key_exists('key', $ar)`              
Isset #3              | `$d->has('key')`                                  | -                                           
Nested key  #1        | `$d->find('inner.inner.prop', $default)`          | `$ar['inner']['inner']['prop']` (error?)    
Nested key  #2        | `$d->inner['inner']['prop']`                      | -                                           
Nested key  #3        | `$d['inner']['inner']['prop']`                    | -                                           
Export to Serialized  | `echo (new Data([/* ... */]))`                    | `echo serialize([/* ... */])`               
Export to JSON        | `echo (new JSON([/* ... */]))` (readable)         | `echo json_encode([/* ... */])`             
Export to Yml         | `echo (new Yml ([/* ... */]))` (readable)         | -                                           
Export to Ini         | `echo (new Ini([/* ... */]))` (readable)          | -                                           
Export to PHP Code    | `echo (new PHPArray ([/* ... */]))` (readable)    | -                                           
JSON                  | **+**                                             | -                                           
Filters               | **+**                                             | -                                           
Search                | **+**                                             | -                                           
Flatten Recursive     | **+**                                             | -                                           


#### Methods

```php
use function JBZoo\Data\data;
use function JBZoo\Data\ini;
use function JBZoo\Data\json;
use function JBZoo\Data\phpArray;
use function JBZoo\Data\yml;

$config = data([/* Assoc Array */]);       // Any PHP-array or simple object, serialized data
$config = ini('./configs/some.ini');       // Load configs from ini file (or string, or simple array)
$config = yml('./configs/some.yml');       // Yml (or string, or simple array). Parsed with Symfony/Yaml Component.
$config = json('./configs/some.json');     // JSON File (or string, or simple array)
$config = phpArray('./configs/some.php');  // PHP-file that must return array

// Read
$config->get('key', 42);                   // Returns value if it exists oR returns default value
$config['key'];                            // As regular array
$config->key;                              // As regular object

// Read nested values without PHP errors
$config->find('deep.config.key', 42);      // Gets `$config['very']['deep']['config']['key']` OR returns default value

// Write
$config->set('key', 42);
$config['key'] = 42;
$config->key = 42;

// Isset
$config->has('key');
isset($config['key']);
isset($config->key);

// Unset
$config->remove('key');
unset($config['key']);
unset($config->key);

```


#### Filter values (required JBZoo/Utils)

List of filters - [JBZoo/Utils/Filter](https://github.com/JBZoo/Utils/blob/master/src/Filter.php)
 * `bool` -  Converts many english words that equate to true or false to boolean.
 * `int` - Smart converting to integer
 * `float` - Smart converting to float
 * `digits` - Leaves only "0-9"
 * `alpha` - Leaves only "a-zA-Z"
 * `alphanum` - Combination of `digits` and `alpha` 
 * `base64` - Returns only chars which are compatible with base64
 * `path` - Clean FS path
 * `trim` - Extend trim
 * `arr` - Converting to array
 * `cmd` - Cleanup system command (CLI)
 * `email` - Returns cleaned up email or null
 * `strip` - Strip tags
 * `alias` - Sluggify
 * `low` - String to lower (uses mbstring or symfony polyfill)
 * `up` - String to upper (uses mbstring or symfony polyfill)
 * `clean` - Returns safe string
 * `html` - HTML escaping
 * `xml` - XML escaping
 * `esc` - Escape chars for UTF-8
 * `function($value) { return $value; }` - Your custom callback function


```php
$config->get('key', 42, 'int');         // Smart converting to integer
$config->find('key', 42, 'float');      // To float
$config->find('no', 'yes', 'bool');     // Smart converting popular word to boolean value
$config->get('key', 42, 'strip, trim'); // Chain of filters

// Your custom handler
$config->get('key', 42, function($value) {
    return (float)str_replace(',', '.', $value);
});
```


#### Utility methods
```php
$config->search($needle);       // Find a value also in nested arrays/objects
$config->flattenRecursive();    // Return flattened array copy. Keys are <b>NOT</b> preserved.
```

#### Export to pretty-print format
```php
echo $config;

$result = '' . $config;
$result = (string)$config;
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
