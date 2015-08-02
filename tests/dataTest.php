<?php
/**
 * Data
 *
 * Copyright (c) 2015, Denis Smetannikov <denis@jbzoo.com>.
 *
 * @package   SimpleTypes
 * @author    Denis Smetannikov <denis@jbzoo.com>
 * @copyright 2015 Denis Smetannikov <denis@jbzoo.com>
 * @link      http://github.com/SmetDenis/Data
 */

namespace SmetDenis\Data;

/**
 * Class DataTest
 * @package SmetDenis\Data
 */
class DataTest extends PHPUnit
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

    public function testCreate()
    {
        $data = new Data($this->test);

        self::assertInstanceOf('\IteratorAggregate', $data);
        self::assertInstanceOf('\ArrayAccess', $data);
        self::assertInstanceOf('\Serializable', $data);
        self::assertInstanceOf('\Countable', $data);
        self::assertTrue(is_object($data));
    }

    public function testHas()
    {
        $data = new Data($this->test);

        self::assertFalse($data->has('undefined'));
        self::assertTrue($data->has('null'));
        self::assertTrue($data->has('string-empty'));
        self::assertTrue($data->has('string-zero'));
        self::assertTrue($data->has('number-zero'));
        self::assertTrue($data->has('array_empty'));
        self::assertTrue($data->has('array_not_empty'));
    }

    public function testSerialize()
    {
        $data = new Data();
        self::assertEquals('a:0:{}', '' . $data);
    }

    public function testGet()
    {
        $data = new Data($this->test);

        self::assertEquals(10, $data->get('number'));
        self::assertEquals('qwerty', $data->get('string'));
        self::assertTrue($data->get('bool-true'));
        self::assertFalse($data->get('bool-false'));
        self::assertTrue(is_array($data->get('nested')));
        self::assertEquals('wsxzaq', $data->get('nested.value-1'));
        self::assertEquals('ytrewq', $data->get('nested.sub.qwerty'));

        // undefined
        self::assertNull($data->get('undefined'));
        self::assertEquals('some-value', $data->get('undefined', 'some-value'));
        self::assertNull($data->get('undefined', null));
    }

    public function testSet()
    {
        // methods
        $data = new Data($this->test);
        self::assertEquals(10, $data->get('number'));
        $data->set('number', 'qqq');
        self::assertEquals('qqq', $data->get('number'));

        // like array
        $data = new Data($this->test);
        self::assertEquals(10, $data['number']);
        $data['number'] = 'qqq';
        self::assertEquals('qqq', $data['number']);

        // like object
        $data = new Data($this->test);
        self::assertEquals(10, $data->number);
        $data->number = 'qqq';
        self::assertEquals('qqq', $data->number);
    }

    public function testFind()
    {
        $data = new Data($this->test);
        self::assertSame(array('sub' => 'sub-value', 'sub.sub' => 'sub-value-2'), $data->get('sub'));
        self::assertSame(array('sub' => 'sub-value', 'sub.sub' => 'sub-value-2'), $data->find('sub'));
        self::assertNull($data->find('sub.sub.sub'));
        self::assertEquals('sub-value', $data->find('sub.sub'));
        self::assertEquals(array(
            'key-1' => 'deep-value',
            'sub'   => array(
                'key-sub' => 'really-deep-value',
            ),
        ), $data->find('array.sub-sub'));
        self::assertEquals('sub-prop-value-2', $data->find('objects.sub.prop-2'));

        self::assertSame(array(
            'prop-1' => 'sub-prop-value-1',
            'prop-2' => 'sub-prop-value-2',
        ), (array)$data->find('objects.sub'));

        self::assertEquals('tttttt', $data->find('undefined', 'tttttt'));
        self::assertEquals('ffffff', $data->find('undefined.key', 'ffffff'));

        self::assertEquals('gggggg', $data->find('data.key-3', 'gggggg'));
        self::assertEquals('data-value-2', $data->find('data.key-2'));
    }

    public function testRemove()
    {
        $data = new Data($this->test);
        self::assertEquals('qwerty', $data->get('string'));
        $data->remove('string');
        self::assertFalse($data->has('string'));
        self::assertNull($data->get('string'));
    }

    public function testIsset()
    {
        $data = new Data($this->test);
        self::assertTrue(isset($data['string']));
        self::assertFalse(isset($data['undefined']));

        self::assertTrue(isset($data->string));
        self::assertFalse(isset($data->undefined));
    }

    public function testEmpty()
    {
        $data = new Data($this->test);
        self::assertFalse(empty($data['string']));
        self::assertTrue(empty($data['undefined']));

        self::assertFalse(empty($data->string));
        self::assertTrue(empty($data->undefined));
    }

    public function testUnset()
    {
        // like object
        $data = new Data($this->test);
        self::assertEquals('qwerty', $data->get('string'));
        unset($data->string);
        self::assertFalse($data->has('string'));

        // like array
        $data = new Data($this->test);
        self::assertEquals('qwerty', $data['string']);
        unset($data['string']);
        self::assertFalse($data->has('string'));
    }

    public function testSearch()
    {
        // like object
        $data = new Data($this->test);
        self::assertFalse($data->search('q1w2e3'));
        self::assertEquals('nested.sub.qwerty', $data->search('ytrewq'));
    }

    public function testFlattenRecursive()
    {
        // like object
        $data = new Data(array(
            'number' => 10,
            'string' => 'qwerty',
            'sub'    => array(
                'sub'     => 'sub-value',
                'sub-sub' => array(
                    'sub-key' => 'sub-sub-value',
                ),
            ),
        ));

        self::assertSame(array(10, 'qwerty', 'sub-value', 'sub-sub-value'), $data->flattenRecursive());
    }

}
