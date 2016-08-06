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
use JBZoo\Data\JSON;

/**
 * Class JsonDataTest
 * @package JBZoo\Data
 */
class JsonDataTest extends PHPUnit
{

    protected $test = array();

    public function setUp()
    {
        $this->test = array(
            // simular
            'string-empty'      => '',
            'string-zero'       => '0',
            'string'            => 'qwerty',
            'number-zero'       => 0,
            'number'            => 10,
            'bool-true'         => true,
            'bool-false'        => false,
            'null'              => null,

            // array
            'array_empty'       => array(),
            'array_not_empty'   => array(
                '123' => '123321',
            ),

            // objects
            'objects'           => (object)array(
                'prop-1' => 'prop-value-1',
                'prop-2' => 'prop-value-2',
                'sub'    => (object)array(
                    'prop-1' => 'sub-prop-value-1',
                    'prop-2' => 'sub-prop-value-2',
                ),
            ),

            // real nested
            'sub'               => array(
                'sub'     => 'sub-value',
                'sub.sub' => 'sub-value-2',
            ),

            'array'             => array(
                'sub'     => 'array-value',
                'sub-sub' => array(
                    'key-1' => 'deep-value',
                    'sub'   => array(
                        'key-sub' => 'really-deep-value',
                    ),
                ),
            ),

            'data'              => new Data(array(
                'key-1' => 'data-value-1',
                'key-2' => 'data-value-2',
            )),

            // real nested
            'nested'            => array(
                'value-1' => 'val-1',
                'value-2' => 'val-2',
                'sub'     => array(
                    'qwerty' => 'deep-value',
                ),
            ),

            // pseudo nested
            'nested.value-1'    => 'wsxzaq',
            'nested.value-2'    => 'qazxsw',
            'nested.sub.qwerty' => 'ytrewq',
        );
    }

    public function testToString()
    {
        $data = new JSON($this->test);

        $jsonTest  = (string)$data;
        $jsonValid = openFile('./tests/resource/data.json');

        is($jsonValid, $jsonTest);
    }

    public function testJson()
    {
        $dataValid = openFile('./tests/resource/data.json');
        $data      = new JSON($dataValid);

        is($dataValid, (string)$data);
    }

    public function testPropsVisible()
    {
        $data = new JSON($this->test);
        isTrue(count(get_object_vars($data)) > 0);
    }
}
