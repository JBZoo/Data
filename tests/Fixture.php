<?php

/**
 * JBZoo Toolbox - Data
 *
 * This file is part of the JBZoo Toolbox project.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @package    Data
 * @license    MIT
 * @copyright  Copyright (C) JBZoo.com, All rights reserved.
 * @link       https://github.com/JBZoo/Data
 * @author     Denis Smetannikov <denis@jbzoo.com>
 */

/**
 * Class Fixture
 * @package JBZoo\PHPunit
 */
class Fixture
{
    /**
     * @return array
     */
    public static function createRandomArray()
    {
        $data = [
            'prop'  => uniqid('', true),
            'prop1' => uniqid('', true),
            'prop2' => uniqid('', true),
            'prop3' => uniqid('', true),
            'prop4' => uniqid('', true),
            'inner' => [
                'prop'  => uniqid('', true),
                'prop1' => uniqid('', true),
                'prop2' => uniqid('', true),
                'prop3' => uniqid('', true),
                'prop4' => uniqid('', true),
                'inner' => [
                    'prop'  => uniqid('', true),
                    'prop1' => uniqid('', true),
                    'prop2' => uniqid('', true),
                    'prop3' => uniqid('', true),
                    'prop4' => uniqid('', true),
                ],
            ],
        ];

        for ($i = 0; $i <= 999; $i++) {
            $data['inner' . $i] = [
                'prop'  => uniqid('', true),
                'prop1' => uniqid('', true),
                'prop2' => uniqid('', true),
                'prop3' => uniqid('', true),
                'prop4' => uniqid('', true),
            ];
        }

        return $data;
    }
}
