<?php
/**
 * JBZoo Data
 *
 * This file is part of the JBZoo CCK package.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @package   Data
 * @license   MIT
 * @copyright Copyright (C) JBZoo.com,  All rights reserved.
 * @link      https://github.com/JBZoo/Data
 * @author    Denis Smetannikov <denis@jbzoo.com>
 */

namespace JBZoo\PHPUnit;

use JBZoo\Data\Data;

/**
 * Class YmlDataTest
 * @package JBZoo\Data
 */
class FilterTest extends PHPUnit
{

    public function testFilter()
    {
        $value = '  123.456 string <i>qwerty</i>  ';

        $data = new Data(array(
            'key'   => $value,
            'array' => array(
                'inner' => $value,
            ),
        ));

        // Default value
        isSame(null, $data->get('undefined'));
        isSame(42.123, $data->get('undefined', 42.123));
        isSame(42, $data->get('undefined', 42.123, 'int'));
        isSame(42.123, $data->get('undefined', '42.123', 'float'));

        // Get & find
        isSame($value, $data->get('key'));
        isSame($value, $data->find('key'));
        isSame($value, $data->find('array.inner'));

        // One filter
        isSame(123, $data->get('key', null, 'int'));
        isSame(123, $data->find('key', null, 'int'));
        isSame(123, $data->find('array.inner', null, 'int'));

        // Several filters
        isSame('stringqwerty', $data->get('key', null, 'strip, trim, alpha'));
        isSame('stringqwerty', $data->find('key', null, 'strip, trim, alpha'));
        isSame('stringqwerty', $data->find('array.inner', null, 'strip, trim, alpha'));

        // Several filters
        isSame('123.456 string qwerty', $data->get('key', null, function ($value) {
            return trim(strip_tags($value));
        }));

        isSame('123.456 string qwerty', $data->find('key', null, function ($value) {
            return trim(strip_tags($value));
        }));

        isSame('123.456 string qwerty', $data->find('array.inner', null, function ($value) {
            return trim(strip_tags($value));
        }));
    }
}
