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
use JBZoo\Profiler\Benchmark;
use JBZoo\Utils\Env;

/**
 * Class BenchmarkTest
 * @package JBZoo\PHPUnit
 */
class BenchmarkTest extends PHPUnit
{

    protected $_data = array();

    protected function setUp()
    {
        $data = array(
            'prop'  => uniqid('', true),
            'prop1' => uniqid('', true),
            'prop2' => uniqid('', true),
            'prop3' => uniqid('', true),
            'prop4' => uniqid('', true),
            'inner' => array(
                'prop'  => uniqid('', true),
                'prop1' => uniqid('', true),
                'prop2' => uniqid('', true),
                'prop3' => uniqid('', true),
                'prop4' => uniqid('', true),
                'inner' => array(
                    'prop'  => uniqid('', true),
                    'prop1' => uniqid('', true),
                    'prop2' => uniqid('', true),
                    'prop3' => uniqid('', true),
                    'prop4' => uniqid('', true),
                ),
            ),
        );

        for ($i = 0; $i <= 99; $i++) {
            $data['inner' . $i] = array(
                'prop'  => uniqid('', true),
                'prop1' => uniqid('', true),
                'prop2' => uniqid('', true),
                'prop3' => uniqid('', true),
                'prop4' => uniqid('', true),
            );
        }

        $this->_data = $data;
    }

    public function testCreate()
    {
        $array = $this->_data;

        Benchmark::compare(array(
            'Array'          => function () use ($array) {
                $var = $array; // for clean experiment
                return $var;
            },
            'Data'           => function () use ($array) {
                $var = new Data($array);
                return $var;
            },
            'ArrayObject'    => function () use ($array) {
                $var = new \ArrayObject($array);
                return $var;
            },
            'ArrayObjectExt' => function () use ($array) {
                $var = new \ArrayObjectExt($array);
                return $var;
            },
        ), array('name' => 'Create var', 'count' => 10000));
    }

    public function testGet()
    {
        $array     = $this->_data;
        $data      = new Data($this->_data);
        $arrobj    = new \ArrayObject($this->_data);
        $arrobjExt = new \ArrayObjectExt($this->_data);

        if (Env::isHHVM()) {
            $data->setFlags(0);
        }

        Benchmark::compare(array(
            // Simple array
            'Array::clean'              => function () use ($array) {
                return $array['prop'];
            },
            'Array::@'                  => function () use ($array) {
                return @$array['prop'];
            },
            'Array::isset'              => function () use ($array) {
                return isset($array['prop']) ? $array['prop'] : null;
            },
            'Array::array_key_exists'   => function () use ($array) {
                return array_key_exists('prop', $array) ? $array['prop'] : null;
            },

            // ArrayObject
            'ArrayObject::array'        => function () use ($arrobj) {
                return $arrobj['prop'];
            },
            'ArrayObject::offsetGet'    => function () use ($arrobj) {
                return $arrobj->offsetGet('prop');
            },

            // ArrayObjectExt
            'ArrayObjectExt::array'     => function () use ($arrobjExt) {
                return $arrobjExt['prop'];
            },
            'ArrayObjectExt::offsetGet' => function () use ($arrobjExt) {
                return $arrobjExt->offsetGet('prop');
            },

            // JBZoo/Data
            'Data::get'                 => function () use ($data) {
                return $data->get('prop');
            },
            'Data::arrow'               => function () use ($data) {
                return $data->prop;
            },
            'Data::array'               => function () use ($data) {
                return $data['prop'];
            },
            'Data::find'                => function () use ($data) {
                return $data->find('prop');
            },
            'Data::offsetGet'           => function () use ($data) {
                return $data->offsetGet('prop');
            },
        ), array('name' => 'Get defined var', 'count' => 10000));
    }

    public function testGetInner()
    {
        $array     = $this->_data;
        $data      = new Data($this->_data);
        $arrobj    = new \ArrayObject($this->_data);
        $arrobjExt = new \ArrayObjectExt($this->_data);

        Benchmark::compare(array(
            // Simple array
            'Array::clean'            => function () use ($array) {
                return $array['inner']['inner']['prop'];
            },
            'Array::@'                => function () use ($array) {
                return @$array['inner']['inner']['prop'];
            },
            'Array::isset'            => function () use ($array) {
                return isset($array['inner']['inner']['prop']) ? $array['inner']['inner']['prop'] : null;
            },
            'Array::array_key_exists' => function () use ($array) {

                if (array_key_exists('inner', $array)) {
                    if (array_key_exists('inner', $array['inner'])) {
                        if (array_key_exists('prop', $array['inner']['inner'])) {
                            return $array['inner']['inner']['prop'];
                        }
                    }
                }

                return null;
            },

            // ArrayObject
            'ArrayObject::array'      => function () use ($arrobj) {
                return $arrobj['inner']['inner']['prop'];
            },

            // ArrayObjectExt
            'ArrayObjectExt::array'   => function () use ($arrobjExt) {
                return $arrobjExt['inner']['inner']['prop'];
            },

            // Data
            'Data::arrow'             => function () use ($data) {
                return $data->inner['inner']['prop'];
            },
            'Data::array'             => function () use ($data) {
                return $data['inner']['inner']['prop'];
            },
            'Data::find'              => function () use ($data) {
                return $data->find('inner.inner.prop');
            },
        ), array('name' => 'Get inner var', 'count' => 10000));
    }

    public function testGetUndefined()
    {
        $array     = $this->_data;
        $data      = new Data($this->_data);
        $arrobj    = new \ArrayObject($this->_data);
        $arrobjExt = new \ArrayObjectExt($this->_data);

        Benchmark::compare(array(
            // Simple array
            'array::@'                   => function () use ($array) {
                return @$array['undefined'];
            },
            'array::isset'               => function () use ($array) {
                return isset($array['undefined']) ? $array['undefined'] : null;
            },
            'array::array_key_exists'    => function () use ($array) {
                return array_key_exists('undefined', $array) ? $array['undefined'] : null;
            },

            // ArrayObject
            'ArrayObject::arrow@'        => function () use ($arrobj) {
                return @$arrobj->undefined;
            },
            'ArrayObject::array@'        => function () use ($arrobj) {
                return @$arrobj['undefined'];
            },
            'ArrayObject::offsetGet@'    => function () use ($arrobj) {
                return @$arrobj->offsetGet('undefined');
            },

            // ArrayObjectExt
            'ArrayObjectExt::arrow@'     => function () use ($arrobjExt) {
                return @$arrobjExt->undefined;
            },
            'ArrayObjectExt::array@'     => function () use ($arrobjExt) {
                return @$arrobjExt['undefined'];
            },
            'ArrayObjectExt::offsetGet@' => function () use ($arrobjExt) {
                return @$arrobjExt->offsetGet('undefined');
            },

            // JBZoo/Data
            'Data::get'                  => function () use ($data) {
                return $data->get('undefined');
            },
            'Data::arrow@'               => function () use ($data) {
                return @$data->undefined;
            },
            'Data::array@'               => function () use ($data) {
                return @$data['undefined'];
            },
            'Data::find'                 => function () use ($data) {
                return $data->find('undefined');
            },
            'Data::offsetGet@'           => function () use ($data) {
                return @$data->offsetGet('undefined');
            },
        ), array('name' => 'Get undefined var', 'count' => 10000));
    }


    public function testForReadme()
    {
        $times = 10000;
        $this->_data = array(
            'prop'  => uniqid('', true),
            'prop1' => uniqid('', true),
            'prop2' => uniqid('', true),
            'prop3' => uniqid('', true),
            'prop4' => uniqid('', true),
            'inner' => array(
                'prop'  => uniqid('', true),
                'prop1' => uniqid('', true),
                'prop2' => uniqid('', true),
                'prop3' => uniqid('', true),
                'prop4' => uniqid('', true),
                'inner' => array(
                    'prop'  => uniqid('', true),
                    'prop1' => uniqid('', true),
                    'prop2' => uniqid('', true),
                    'prop3' => uniqid('', true),
                    'prop4' => uniqid('', true),
                ),
            ),
        );

        $array  = $this->_data;
        $data   = new Data($this->_data);
        $arrobj = new \ArrayObject($this->_data);

        Benchmark::compare(array(
            'Array'       => function () use ($array) {
                $var = $array; // for clean experiment
                return $var;
            },
            'ArrayObject' => function () use ($array) {
                $var = new \ArrayObject($array);
                return $var;
            },
            'Data'        => function () use ($array) {
                $var = new Data($array);
                return $var;
            },
        ), array('name' => 'For Readme: Create', 'count' => $times));


        Benchmark::compare(array(
            'Array'       => function () use ($array) {
                return array_key_exists('prop', $array) ? $array['prop'] : null;
            },
            'ArrayObject' => function () use ($arrobj) {
                return $arrobj->offsetGet('prop');
            },
            'Data'        => function () use ($data) {
                return $data->get('prop');
            },
        ), array('name' => 'For Readme: Get by key', 'count' => $times));


        Benchmark::compare(array(
            'Array'       => function () use ($array) {
                if (
                    array_key_exists('inner', $array) &&
                    array_key_exists('inner', $array['inner']) &&
                    array_key_exists('prop', $array['inner']['inner'])
                ) {
                    return $array['inner']['inner']['prop'];
                }

                return 42;
            },
            'ArrayObject' => function () use ($arrobj) {
                if (
                    array_key_exists('inner', $arrobj) &&
                    array_key_exists('inner', $arrobj['inner']) &&
                    array_key_exists('prop', $arrobj['inner']['inner'])
                ) {
                    return $arrobj['inner']['inner']['prop'];
                }

                return 42;
            },
            'Data'        => function () use ($data) {
                return $data->find('inner.inner.prop', 42);
            },
        ), array('name' => 'For Readme: Find nested defined var', 'count' => $times));

        Benchmark::compare(array(
            'Array'       => function () use ($array) {
                if (
                    array_key_exists('inner', $array) &&
                    array_key_exists('inner', $array['inner']) &&
                    array_key_exists('undefined', $array['inner']['inner'])
                ) {
                    return $array['inner']['inner']['prop'];
                }

                return 42;
            },
            'ArrayObject' => function () use ($arrobj) {
                if (
                    array_key_exists('inner', $arrobj) &&
                    array_key_exists('inner', $arrobj['inner']) &&
                    array_key_exists('undefined', $arrobj['inner']['inner'])
                ) {
                    return $arrobj['inner']['inner']['undefined'];
                }

                return 42;
            },
            'Data'        => function () use ($data) {
                return $data->find('inner.inner.undefined', 42);
            },
        ), array('name' => 'For Readme: Find nested undefined var', 'count' => $times));
    }
}
