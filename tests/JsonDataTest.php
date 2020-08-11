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

namespace JBZoo\PHPUnit;

use JBZoo\Data\Data;
use JBZoo\Data\JSON;

use function JBZoo\Data\json;

/**
 * Class JsonDataTest
 *
 * @package JBZoo\Data
 */
class JsonDataTest extends PHPUnit
{
    protected $test = [];

    protected function setUp(): void
    {
        $this->test = [
            // simular
            'string-empty'    => '',
            'string-zero'     => '0',
            'string'          => 'qwerty',
            'number-zero'     => 0,
            'number'          => 10,
            'bool-true'       => true,
            'bool-false'      => false,
            'null'            => null,

            // array
            'array_empty'     => [],
            'array_not_empty' => [
                '123' => '123321',
            ],

            // objects
            'objects'         => (object)[
                'prop-1' => 'prop-value-1',
                'prop-2' => 'prop-value-2',
                'sub'    => (object)[
                    'prop-1' => 'sub-prop-value-1',
                    'prop-2' => 'sub-prop-value-2',
                ],
            ],

            // real nested
            'sub'             => [
                'sub'     => 'sub-value',
                'sub.sub' => 'sub-value-2',
            ],

            'array' => [
                'sub'     => 'array-value',
                'sub-sub' => [
                    'key-1' => 'deep-value',
                    'sub'   => [
                        'key-sub' => 'really-deep-value',
                    ],
                ],
            ],

            'data'              => new Data([
                'key-1' => 'data-value-1',
                'key-2' => 'data-value-2',
            ]),

            // real nested
            'nested'            => [
                'value-1' => 'val-1',
                'value-2' => 'val-2',
                'sub'     => [
                    'qwerty' => 'deep-value',
                ],
            ],

            // pseudo nested
            'nested.value-1'    => 'wsxzaq',
            'nested.value-2'    => 'qazxsw',
            'nested.sub.qwerty' => 'ytrewq',
        ];
    }

    public function testToString()
    {
        $data = new JSON($this->test);

        $jsonTest = (string)$data;
        $jsonValid = openFile('./tests/resource/data.json');

        is($jsonValid, $jsonTest);
    }

    public function testJson()
    {
        $dataValid = openFile('./tests/resource/data.json');
        $data = new JSON($dataValid);

        is($dataValid, (string)$data);
    }

    public function testPropsVisible()
    {
        $data = new JSON($this->test);
        isTrue(count((array)$data) > 0);
    }

    public function testBitInt()
    {
        $value = 123456789012345678901234567890;

        $bigIntAsString = ['value' => '123456789012345678901234567890'];
        $bigInt = ['value' => $value];
        $jsonBigIntAsString = '{"value":"123456789012345678901234567890"}';
        $jsonBigInt = '{"value":123456789012345678901234567890}';


        $expected = json($jsonBigIntAsString);
        $actual = json($jsonBigInt);
        isSame($bigIntAsString, $expected->getArrayCopy());
        isSame($expected->getArrayCopy(), $actual->getArrayCopy());

        //isSame(json($bigIntAsString)->getArrayCopy(), json($bigInt)->getArrayCopy()); // TODO: needs to think
    }
}
