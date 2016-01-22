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
 * Class BenchmarkTest
 * @package JBZoo\PHPUnit
 */
class BenchmarkTest extends PHPUnit
{

    protected $_data = array();

    protected function setUp()
    {
        $this->_data = array(
            'prop'  => 'qwerty123',
            'prop1' => 'qwerty123',
            'prop2' => 'qwerty123',
            'prop3' => 'qwerty123',
            'prop4' => 'qwerty123',
            'inner' => array(
                'prop'  => 'qwerty123',
                'prop1' => 'qwerty123',
                'prop2' => 'qwerty123',
                'prop3' => 'qwerty123',
                'prop4' => 'qwerty123',
                'inner' => array(
                    'prop'  => 'qwerty123',
                    'prop1' => 'qwerty123',
                    'prop2' => 'qwerty123',
                    'prop3' => 'qwerty123',
                    'prop4' => 'qwerty123',
                ),
            ),
        );
    }

    public function testCreate()
    {
        $array = $this->_data;

        runBench(array(
            'Array'       => function () use ($array) {
                $var = $array; // for clean experiment
                return $var;
            },
            'Data'        => function () use ($array) {
                $var = new Data($array);
                return $var;
            },
            'ArrayObject' => function () use ($array) {
                $var = new \ArrayObject($array);
                return $var;
            },
        ), array('name' => 'Create var', 'count' => 10000));
    }

    public function testGet()
    {
        $array  = $this->_data;
        $data   = new Data($this->_data);
        $arrobj = new \ArrayObject($this->_data);

        runBench(array(
            // Simple array
            'Array::clean'            => function () use ($array) {
                return $array['prop'];
            },
            'Array::@'                => function () use ($array) {
                return @$array['prop'];
            },
            'Array::isset'            => function () use ($array) {
                return isset($array['prop']) ? $array['prop'] : null;
            },
            'Array::array_key_exists' => function () use ($array) {
                return array_key_exists('prop', $array) ? $array['prop'] : null;
            },

            // ArrayObject
            'ArrayObject::array'      => function () use ($arrobj) {
                return $arrobj['prop'];
            },
            'ArrayObject::offsetGet'  => function () use ($arrobj) {
                return $arrobj->offsetGet('prop');
            },

            // JBZoo/Data
            'Data::get'               => function () use ($data) {
                return $data->get('prop');
            },
            'Data::arrow'             => function () use ($data) {
                return $data->prop;
            },
            'Data::array'             => function () use ($data) {
                return $data['prop'];
            },
            'Data::find'              => function () use ($data) {
                return $data->find('prop');
            },
            'Data::offsetGet'         => function () use ($data) {
                return $data->offsetGet('prop');
            },
        ), array('name' => 'Get defined var', 'count' => 10000));
    }

    public function testGetUndefined()
    {
        $array  = $this->_data;
        $data   = new Data($this->_data);
        $arrobj = new \ArrayObject($this->_data);

        runBench(array(
            // Simple array
            'array::@'                => function () use ($array) {
                return @$array['undefined'];
            },
            'array::isset'            => function () use ($array) {
                return isset($array['undefined']) ? $array['undefined'] : null;
            },
            'array::array_key_exists' => function () use ($array) {
                return array_key_exists('undefined', $array) ? $array['undefined'] : null;
            },

            // ArrayObject/Data
            'ArrayObject::arrow@'     => function () use ($arrobj) {
                return @$arrobj->undefined;
            },
            'ArrayObject::array@'     => function () use ($arrobj) {
                return @$arrobj['undefined'];
            },
            'ArrayObject::offsetGet@' => function () use ($arrobj) {
                return @$arrobj->offsetGet('undefined');
            },

            // JBZoo/Data
            'Data::get'               => function () use ($data) {
                return $data->get('undefined');
            },
            'Data::arrow@'            => function () use ($data) {
                return @$data->undefined;
            },
            'Data::array@'            => function () use ($data) {
                return @$data['undefined'];
            },
            'Data::find'              => function () use ($data) {
                return $data->find('undefined');
            },
            'Data::offsetGet@'        => function () use ($data) {
                return @$data->offsetGet('undefined');
            },
        ), array('name' => 'Get undefined var', 'count' => 10000));
    }
}