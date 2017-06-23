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

namespace JBZoo\PHPunit;

use JBZoo\Data\Data;
use JBZoo\Data\JSON;
use function JBZoo\Data\json;

/**
 * Class DataTest
 * @package JBZoo\Data
 */
class DataTest extends PHPUnit
{
    protected $_test = array();

    public function setUp()
    {
        $this->_test = array(
            // simular
            'string-zero'       => '0',
            'string-empty'      => '',
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
        $data = new Data($this->_test);

        isClass('\IteratorAggregate', $data);
        isClass('\ArrayAccess', $data);
        isClass('\Serializable', $data);
        isClass('\Countable', $data);
        isClass('\ArrayObject', $data);

        isTrue(is_object($data)); // :)
        isFalse(is_array($data)); // :(

        foreach ($data as $key => $value) { // like array
            isSame('string-zero', $key);
            isSame('0', $value);
            break;
        }
    }

    public function testHas()
    {
        $data = new Data($this->_test);

        isFalse($data->has('undefined'));
        isTrue($data->has('null'));
        isTrue($data->has('string-empty'));
        isTrue($data->has('string-zero'));
        isTrue($data->has('number-zero'));
        isTrue($data->has('array_empty'));
        isTrue($data->has('array_not_empty'));
    }

    public function testSerialize()
    {
        $data = new Data();
        is('a:0:{}', (string)$data);
    }

    public function testUnSerialize()
    {
        $data = new Data(serialize(array()));
        isSame(serialize(array()), (string)$data);
    }

    public function testGet()
    {
        $data = new Data($this->_test);

        is(10, $data->get('number'));
        is('qwerty', $data->get('string'));
        isTrue($data->get('bool-true'));
        isFalse($data->get('bool-false'));
        isTrue(is_array($data->get('nested')));
        is('wsxzaq', $data->get('nested.value-1'));
        is('ytrewq', $data->get('nested.sub.qwerty'));

        // undefined
        isNull($data->get('undefined'));
        is('some-value', $data->get('undefined', 'some-value'));
        isNull($data->get('undefined', null));
    }

    public function testSet()
    {
        // methods
        $data = new Data($this->_test);
        is(10, $data->get('number'));
        $data->set('number', 'qqq');
        is('qqq', $data->get('number'));

        // like array
        $data = new Data($this->_test);
        is(10, $data['number']);
        $data['number'] = 'qqq';
        is('qqq', $data['number']);

        // like object
        $data = new Data($this->_test);
        is(10, $data->number);
        $data->number = 'qqq';
        is('qqq', $data->number);
    }

    public function testFind()
    {
        $data = new Data($this->_test);
        isSame(array('sub' => 'sub-value', 'sub.sub' => 'sub-value-2'), $data->get('sub'));
        isSame(array('sub' => 'sub-value', 'sub.sub' => 'sub-value-2'), $data->find('sub'));
        isNull($data->find('sub.sub.sub'));
        is('sub-value', $data->find('sub.sub'));
        is(array(
            'key-1' => 'deep-value',
            'sub'   => array(
                'key-sub' => 'really-deep-value',
            ),
        ), $data->find('array.sub-sub'));
        is('sub-prop-value-2', $data->find('objects.sub.prop-2'));

        isSame(array(
            'prop-1' => 'sub-prop-value-1',
            'prop-2' => 'sub-prop-value-2',
        ), (array)$data->find('objects.sub'));

        is('tttttt', $data->find('undefined', 'tttttt'));
        is('ffffff', $data->find('undefined.key', 'ffffff'));

        is('gggggg', $data->find('data.key-3', 'gggggg'));
        is('data-value-2', $data->find('data.key-2'));
    }

    public function testRemove()
    {
        $data = new Data($this->_test);
        is('qwerty', $data->get('string'));
        $data->remove('string');
        isFalse($data->has('string'));
        isNull($data->get('string'));
    }

    public function testIsset()
    {
        $data = new Data($this->_test);
        isTrue(isset($data['string']));
        isFalse(isset($data['undefined']));

        isTrue(isset($data->string));
        isFalse(isset($data->undefined));
    }

    public function testEmpty()
    {
        $data = new Data($this->_test);
        isFalse(empty($data['string']));
        isTrue(empty($data['undefined']));

        isFalse(empty($data->string));
        isTrue(empty($data->undefined));
    }

    public function testUnset()
    {
        // like object
        $data = new Data($this->_test);
        is('qwerty', $data->get('string'));
        unset($data->string);
        isFalse($data->has('string'));

        // like array
        $data = new Data($this->_test);
        is('qwerty', $data['string']);
        unset($data['string']);
        isFalse($data->has('string'));
    }

    public function testSearch()
    {
        // like object
        $data = new Data($this->_test);
        isFalse($data->search('q1w2e3'));
        is('nested.sub.qwerty', $data->search('ytrewq'));
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

        isSame(array(10, 'qwerty', 'sub-value', 'sub-sub-value'), $data->flattenRecursive());
    }

    public function testFindBug()
    {
        $array = array(
            'response' => array(
                'code' => '404',
            ),
        );

        $data = new Data($array);

        isSame('404', $data->find('response.code', 0));
        isSame(404, $data->find('response.code', 0, 'int'));
    }

    public function testNoNotice()
    {
        $data = new Data(array(
            'some_value' => 1,
        ));

        // Methods
        isSame(null, $data->find('qwerty'));
        isSame(null, $data->find('qwerty.qwerty'));
        isSame(null, $data->get('qwerty'));
        isSame(null, $data->get('qwerty.qwerty'));

        // like object
        isSame(null, $data->qwerty);
        isSame(null, $data->qwerty['qwerty']);

        // like array
        isSame(null, $data['qwerty']);
        isSame(null, $data['qwerty']['qwerty']);
        isSame(null, $data['qwerty']['qwerty']['qwerty']['qwerty']);
    }

    public function testIs()
    {
        $data = new Data(array(
            'key'    => 1,
            'nested' => array(
                'key' => null,
            ),
        ));

        isTrue($data->is('key'));
        isTrue($data->is('key', '1'));
        isTrue($data->is('key', 1));
        isTrue($data->is('key', true));
        isTrue($data->is('key', 1, true));
        isTrue($data->is('nested.key', null, true));
        isTrue($data->is('nested.key', false));

        isFalse($data->is('key', '1', true));
        isFalse($data->is('key', 1.0, true));
        isFalse($data->is('nested.key', '1', true));
        isFalse($data->is('nested.key', false, true));
    }

    public function testNumeric()
    {
        $data = new Data(array(
            0        => 0,
            1        => 1,
            'string' => 'test',
            2        => array(
                1,
            ),
            'nested' => array(
                '0', 1,
            ),
        ));

        isSame(0, $data->get(0));
        isSame(1, $data->find('2.0'));
        isSame('0', $data->find('nested.0'));
        isSame(0, $data['0']);
        isSame(1, $data[2][0]);
    }

    public function testPropsVisible()
    {
        $data = new Data($this->_test);
        isTrue(count(get_object_vars($data)) > 0);
    }

    public function testFunctions()
    {
        isClass(Data::class, json());
        isClass(Data::class, json(false));
        isClass(Data::class, json(null));
        isClass(Data::class, json(''));
        isClass(Data::class, json(array()));
        isClass(Data::class, json('{}'));
        isClass(Data::class, json('{"test":42}'));
        isClass(Data::class, json($this->_test));
        isClass(Data::class, json(json()));

        isSame('[]', '' . json());
        //isSame('[false]', '' . json(false));
        isSame('[]', '' . json(null));
        isSame('[]', '' . json(''));
        isSame('[]', '' . json(array()));
        isSame('[]', '' . json('{}'));
        //isSame('{"test":42}', '' . json('{"test":42}'));
        isSame('[]', '' . json(json()));

        isSame(42, json('{"test":42}')->get('test'));

        $origObj = new JSON();
        is($origObj, json($origObj));
    }
}
