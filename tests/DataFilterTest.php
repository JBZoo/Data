<?php

/**
 * JBZoo Toolbox - Data.
 *
 * This file is part of the JBZoo Toolbox project.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @license    MIT
 * @copyright  Copyright (C) JBZoo.com, All rights reserved.
 * @see        https://github.com/JBZoo/Data
 */

declare(strict_types=1);

namespace JBZoo\PHPUnit;

use JBZoo\Data\Data;

class DataFilterTest extends PHPUnit
{
    public function testFilter(): void
    {
        $value = '  123.456 string <i>qwerty</i>  ';

        $data = new Data([
            'key'   => $value,
            'array' => [
                'inner' => $value,
            ],
        ]);

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
        isSame('123.456 string qwerty', $data->get('key', null, static fn ($value) => \trim(\strip_tags($value))));

        isSame('123.456 string qwerty', $data->find('key', null, static fn ($value) => \trim(\strip_tags($value))));

        isSame('123.456 string qwerty', $data->find('array.inner', null, static fn ($value) => \trim(\strip_tags($value))));
    }
}
