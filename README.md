# JBZoo Data  [![Build Status](https://travis-ci.org/JBZoo/Data.svg?branch=master)](https://travis-ci.org/JBZoo/Data) [![Coverage Status](https://coveralls.io/repos/JBZoo/Data/badge.svg?branch=master&service=github)](https://coveralls.io/github/JBZoo/Data?branch=master)

Extended implementation of ArrayObject.
Really useful objects for any config in your system.

[![License](https://poser.pugx.org/JBZoo/Data/license)](https://packagist.org/packages/JBZoo/Data)
[![Latest Stable Version](https://poser.pugx.org/JBZoo/Data/v/stable)](https://packagist.org/packages/JBZoo/Data) [![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/JBZoo/Data/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/JBZoo/Data/?branch=master)

## Install
```sh
composer require jbzoo/data:"1.x-dev"  # Last version
composer require jbzoo/data            # Stable version
```


## Comparing the useful for every day
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
| Pretty JSON format | **+++** | -
| Load data from file | **+++** | -
| Filters | **+++** | -
| Search | **+++** | -
| Flatten Recursive | **+++** | -

## Usage
#### Methods to create config object
```php
require_once './vendor/autoload.php'; // composer autoload.php

// Get needed classes
namespace JBZoo\Data\Data; // And others

// Create
$config     = new Data([/* Assoc Array */]));       // Any PHP-array or simple object, serialized data
$configIni  = new Ini('./configs/some.ini'));       // Load config from ini file (or string, or simple array)
$configYml  = new Yml('./configs/some.yml'));       // Yml (or string, or simple array). Parsed with Symfony/Yaml Component.
$configJSON = new JSON('./configs/some.json'));     // JSON File (or string, or simple array)
$configPHP  = new PHPArry('./configs/some.php'));   // PHP-file that return array

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


#### Filter returned value (required JBZoo/Utils)

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

Result for JSON object
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

Result for PHPArray object
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

Result for Yml object
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

Result for Ini object
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

Result for Data object
```
a:7:{s:5:"empty";s:0:"";s:4:"zero";s:1:"0";s:6:"string";s:1:" ";s:3:"tag";s:42:"<a href="http://google.com">Google.com</a>";s:6:"array1";a:2:{i:0;s:1:"1";i:1;s:1:"2";}s:7:"section";a:1:{s:6:"array2";a:3:{i:0;s:1:"1";i:12;s:1:"2";i:3;s:1:"3";}}s:14:"section.nested";a:1:{s:6:"array3";a:2:{s:2:"00";s:1:"0";s:2:"01";s:1:"1";}}}
```

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
