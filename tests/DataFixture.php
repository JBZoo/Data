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

class DataFixture
{
    public static function createRandomArray(): array
    {
        $data = [
            'prop'  => \uniqid('', true),
            'prop1' => \uniqid('', true),
            'prop2' => \uniqid('', true),
            'prop3' => \uniqid('', true),
            'prop4' => \uniqid('', true),
            'inner' => [
                'prop'  => \uniqid('', true),
                'prop1' => \uniqid('', true),
                'prop2' => \uniqid('', true),
                'prop3' => \uniqid('', true),
                'prop4' => \uniqid('', true),
                'inner' => [
                    'prop'  => \uniqid('', true),
                    'prop1' => \uniqid('', true),
                    'prop2' => \uniqid('', true),
                    'prop3' => \uniqid('', true),
                    'prop4' => \uniqid('', true),
                ],
            ],
        ];

        for ($i = 0; $i <= 999; $i++) {
            $data['inner' . $i] = [
                'prop'  => \uniqid('', true),
                'prop1' => \uniqid('', true),
                'prop2' => \uniqid('', true),
                'prop3' => \uniqid('', true),
                'prop4' => \uniqid('', true),
            ];
        }

        return $data;
    }
}
