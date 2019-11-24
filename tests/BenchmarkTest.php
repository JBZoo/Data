<?php
/**
 * JBZoo Data
 *
 * This file is part of the JBZoo CCK package.
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
use JBZoo\Profiler\Benchmark;
use JBZoo\Utils\Sys;

/**
 * Class BenchmarkTest
 *
 * @package JBZoo\PHPUnit
 */
class BenchmarkTest extends PHPUnit
{
    protected $data = [];

    protected function setUp(): void
    {
        if (Sys::isPHP('7.4')) {
            skip('Needs to replace array_key_exists =>  isset()');
        }

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

        for ($i = 0; $i <= 99; $i++) {
            $data['inner' . $i] = [
                'prop'  => uniqid('', true),
                'prop1' => uniqid('', true),
                'prop2' => uniqid('', true),
                'prop3' => uniqid('', true),
                'prop4' => uniqid('', true),
            ];
        }

        $this->data = $data;
    }

    public function testCreate()
    {
        $array = $this->data;

        Benchmark::compare([
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
        ], ['name' => 'Create var', 'count' => 10000]);
        isTrue(true);
    }

    public function testGet()
    {
        $array = $this->data;
        $data = new Data($this->data);
        $arrObj = new \ArrayObject($this->data);
        $arrObjExt = new \ArrayObjectExt($this->data);

        Benchmark::compare([
            // Simple array
            'Array::clean'              => function () use ($array) {
                return $array['prop'];
            },
            'Array::@'                  => function () use ($array) {
                return @$array['prop'];
            },
            'Array::isset'              => function () use ($array) {
                return $array['prop'] ?? null;
            },
            'Array::array_key_exists'   => function () use ($array) {
                return array_key_exists('prop', $array) ? $array['prop'] : null;
            },

            // ArrayObject
            'ArrayObject::array'        => function () use ($arrObj) {
                return $arrObj['prop'];
            },
            'ArrayObject::offsetGet'    => function () use ($arrObj) {
                return $arrObj->offsetGet('prop');
            },

            // ArrayObjectExt
            'ArrayObjectExt::array'     => function () use ($arrObjExt) {
                return $arrObjExt['prop'];
            },
            'ArrayObjectExt::offsetGet' => function () use ($arrObjExt) {
                return $arrObjExt->offsetGet('prop');
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
        ], ['name' => 'Get defined var', 'count' => 10000]);

        isTrue(true);
    }

    public function testGetInner()
    {
        $array = $this->data;
        $data = new Data($this->data);
        $arrObj = new \ArrayObject($this->data);
        $arrObjExt = new \ArrayObjectExt($this->data);

        Benchmark::compare([
            // Simple array
            'Array::clean'            => function () use ($array) {
                return $array['inner']['inner']['prop'];
            },
            'Array::@'                => function () use ($array) {
                return @$array['inner']['inner']['prop'];
            },
            'Array::isset'            => function () use ($array) {
                return $array['inner']['inner']['prop'] ?? null;
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
            'ArrayObject::array'      => function () use ($arrObj) {
                return $arrObj['inner']['inner']['prop'];
            },

            // ArrayObjectExt
            'ArrayObjectExt::array'   => function () use ($arrObjExt) {
                return $arrObjExt['inner']['inner']['prop'];
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
        ], ['name' => 'Get inner var', 'count' => 10000]);

        isTrue(true);
    }

    public function testGetUndefined()
    {
        $array = $this->data;
        $data = new Data($this->data);
        $arrObj = new \ArrayObject($this->data);
        $arrObjExt = new \ArrayObjectExt($this->data);

        Benchmark::compare([
            // Simple array
            'array::@'                   => function () use ($array) {
                return @$array['undefined'];
            },
            'array::isset'               => function () use ($array) {
                return $array['undefined'] ?? null;
            },
            'array::array_key_exists'    => function () use ($array) {
                return $array['undefined'] ?? null;
            },

            // ArrayObject
            'ArrayObject::arrow@'        => function () use ($arrObj) {
                return @$arrObj->undefined;
            },
            'ArrayObject::array@'        => function () use ($arrObj) {
                return @$arrObj['undefined'];
            },
            'ArrayObject::offsetGet@'    => function () use ($arrObj) {
                return @$arrObj->offsetGet('undefined');
            },

            // ArrayObjectExt
            'ArrayObjectExt::arrow@'     => function () use ($arrObjExt) {
                return @$arrObjExt->undefined;
            },
            'ArrayObjectExt::array@'     => function () use ($arrObjExt) {
                return @$arrObjExt['undefined'];
            },
            'ArrayObjectExt::offsetGet@' => function () use ($arrObjExt) {
                return @$arrObjExt->offsetGet('undefined');
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
        ], ['name' => 'Get undefined var', 'count' => 10000]);

        isTrue(true);
    }


    public function testForReadme()
    {
        $times = 10000;
        $this->data = [
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

        $array = $this->data;
        $data = new Data($this->data);
        $arrObj = new \ArrayObject($this->data);

        Benchmark::compare([
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
        ], ['name' => 'For Readme: Create', 'count' => $times]);


        Benchmark::compare([
            'Array'       => function () use ($array) {
                return array_key_exists('prop', $array) ? $array['prop'] : null;
            },
            'ArrayObject' => function () use ($arrObj) {
                return $arrObj->offsetGet('prop');
            },
            'Data'        => function () use ($data) {
                return $data->get('prop');
            },
        ], ['name' => 'For Readme: Get by key', 'count' => $times]);


        Benchmark::compare([
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
            'ArrayObject' => function () use ($arrObj) {
                if (
                    array_key_exists('inner', $arrObj) &&
                    array_key_exists('inner', $arrObj['inner']) &&
                    array_key_exists('prop', $arrObj['inner']['inner'])
                ) {
                    return $arrObj['inner']['inner']['prop'];
                }

                return 42;
            },
            'Data'        => function () use ($data) {
                return $data->find('inner.inner.prop', 42);
            },
        ], ['name' => 'For Readme: Find nested defined var', 'count' => $times]);

        Benchmark::compare([
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
            'ArrayObject' => function () use ($arrObj) {
                if (
                    array_key_exists('inner', $arrObj) &&
                    array_key_exists('inner', $arrObj['inner']) &&
                    array_key_exists('undefined', $arrObj['inner']['inner'])
                ) {
                    return $arrObj['inner']['inner']['undefined'];
                }

                return 42;
            },
            'Data'        => function () use ($data) {
                return $data->find('inner.inner.undefined', 42);
            },
        ], ['name' => 'For Readme: Find nested undefined var', 'count' => $times]);

        isTrue(true);
    }
}
