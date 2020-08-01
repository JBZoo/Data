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
use JBZoo\Data\Ini;
use JBZoo\Data\JSON;
use JBZoo\Data\PhpArray;
use JBZoo\Data\Yml;

use function JBZoo\Data\data;
use function JBZoo\Data\ini;
use function JBZoo\Data\json;
use function JBZoo\Data\phpArray;
use function JBZoo\Data\yml;

/**
 * Class DataTest
 *
 * @package JBZoo\Data
 */
class DataTest extends PHPUnit
{
    /**
     * @var array
     */
    protected $test = [];

    protected function setUp(): void
    {
        $this->test = [
            // regular types
            'string-zero'     => '0',
            'string-empty'    => '',
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

    public function testCreate()
    {
        $data = new Data($this->test);

        isClass(\IteratorAggregate::class, $data);
        isClass(\ArrayAccess::class, $data);
        isClass(\Serializable::class, $data);
        isClass(\Countable::class, $data);
        isClass(\ArrayObject::class, $data);

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
        $data = new Data($this->test);

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
        $data = new Data(serialize([]));
        isSame(serialize([]), (string)$data);
    }

    public function testAliases()
    {
        $data = new Data($this->test);
        $json = json($this->test);

        // Get
        isSame(10, $data->getInt('number'));
        isSame(10.0, $data->getFloat('number'));
        isSame('10', $data->getString('number'));
        isSame([10], $data->getArray('number'));
        isSame(true, $data->getBool('number'));

        isClass(Data::class, $data->getSelf('sub'));
        isSame(['sub' => 'sub-value', 'sub.sub' => 'sub-value-2'], $data->getSelf('sub')->getArrayCopy());
        isSame(['qwerty'=> 1], (array)$data->getSelf('real-null', ['qwerty'=> 1]));
        isSame(['qwerty'=> 1], (array)$data->getSelf('null', ['qwerty'=> 1]));
        isClass(JSON::class, $json->getSelf('sub'));
        isSame(['sub' => 'sub-value', 'sub.sub' => 'sub-value-2'], $json->getSelf('sub')->getArrayCopy());
        isSame(['qwerty'=> 1], (array)$json->getSelf('real-null', ['qwerty'=> 1]));
        isSame(['qwerty'=> 1], (array)$json->getSelf('null', ['qwerty'=> 1]));

        // Find
        isSame(123321, $data->findInt('array_not_empty.123'));
        isSame(123321.0, $data->findFloat('array_not_empty.123'));
        isSame('123321', $data->findString('array_not_empty.123'));
        isSame(['sub-value'], $data->findArray('sub.sub'));
        isSame(['sub' => 'sub-value', 'sub.sub' => 'sub-value-2'], $data->findArray('sub'));
        isSame(true, $data->findBool('array_not_empty.123'));

        isClass(Data::class, $data->findSelf('sub'));
        isSame(['sub' => 'sub-value', 'sub.sub' => 'sub-value-2'], $data->findSelf('sub')->getArrayCopy());
        isClass(JSON::class, $json->findSelf('sub'));
        isSame(['sub' => 'sub-value', 'sub.sub' => 'sub-value-2'], $json->findSelf('sub')->getArrayCopy());
    }

    public function testGet()
    {
        $data = new Data($this->test);

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
        $data = new Data($this->test);
        is(10, $data->get('number'));
        $data->set('number', 'qqq');
        is('qqq', $data->get('number'));

        // like array
        $data = new Data($this->test);
        is(10, $data['number']);
        $data['number'] = 'qqq';
        is('qqq', $data['number']);

        // like object
        $data = new Data($this->test);
        is(10, $data->number);
        $data->number = 'qqq';
        is('qqq', $data->number);
    }

    public function testFind()
    {
        $data = new Data($this->test);
        isSame(['sub' => 'sub-value', 'sub.sub' => 'sub-value-2'], $data->get('sub'));
        isSame(['sub' => 'sub-value', 'sub.sub' => 'sub-value-2'], $data->find('sub'));
        isNull($data->find('sub.sub.sub'));
        is('sub-value', $data->find('sub.sub'));
        is([
            'key-1' => 'deep-value',
            'sub'   => [
                'key-sub' => 'really-deep-value',
            ],
        ], $data->find('array.sub-sub'));
        is('sub-prop-value-2', $data->find('objects.sub.prop-2'));

        isSame([
            'prop-1' => 'sub-prop-value-1',
            'prop-2' => 'sub-prop-value-2',
        ], (array)$data->find('objects.sub'));

        is('tttttt', $data->find('undefined', 'tttttt'));
        is('ffffff', $data->find('undefined.key', 'ffffff'));

        is('gggggg', $data->find('data.key-3', 'gggggg'));
        is('data-value-2', $data->find('data.key-2'));
    }

    public function testRemove()
    {
        $data = new Data($this->test);
        is('qwerty', $data->get('string'));
        $data->remove('string');
        isFalse($data->has('string'));
        isNull($data->get('string'));
    }

    public function testIsset()
    {
        $data = new Data($this->test);
        isTrue(isset($data['string']));
        isFalse(isset($data['undefined']));

        /** @noinspection MissingIssetImplementationInspection */
        isTrue(isset($data->string));
        /** @noinspection MissingIssetImplementationInspection */
        isFalse(isset($data->undefined));
    }

    public function testEmpty()
    {
        $data = new Data($this->test);
        isFalse(empty($data['string']));
        isTrue(empty($data['undefined']));

        /** @noinspection MissingIssetImplementationInspection */
        isFalse(empty($data->string));
        /** @noinspection MissingIssetImplementationInspection */
        isTrue(empty($data->undefined));
    }

    public function testUnset()
    {
        // like object
        $data = new Data($this->test);
        is('qwerty', $data->get('string'));
        unset($data->string);
        isFalse($data->has('string'));

        // like array
        $data = new Data($this->test);
        is('qwerty', $data['string']);
        unset($data['string']);
        isFalse($data->has('string'));
    }

    public function testSearch()
    {
        // like object
        $data = new Data($this->test);
        isFalse($data->search('q1w2e3'));
        is('nested.sub.qwerty', $data->search('ytrewq'));
    }

    public function testFlattenRecursive()
    {
        // like object
        $data = new Data([
            'number' => 10,
            'string' => 'qwerty',
            'sub'    => [
                'sub'     => 'sub-value',
                'sub-sub' => [
                    'sub-key' => 'sub-sub-value',
                ],
            ],
        ]);

        isSame([10, 'qwerty', 'sub-value', 'sub-sub-value'], $data->flattenRecursive());
    }

    public function testFindBug()
    {
        $array = [
            'response' => [
                'code' => '404',
            ],
        ];

        $data = new Data($array);

        isSame('404', $data->find('response.code', 0));
        isSame(404, $data->find('response.code', 0, 'int'));
    }

    public function testNoNotice()
    {
        $data = new Data(['some_value' => 1]);

        // Methods
        isSame(null, $data->find('qwerty'));
        isSame(null, $data->find('qwerty.qwerty'));
        isSame(null, $data->get('qwerty'));
        isSame(null, $data->get('qwerty.qwerty'));
        isSame(null, $data->qwerty);
    }

    public function testIs()
    {
        $data = new Data([
            'key'    => 1,
            'nested' => [
                'key' => null,
            ],
        ]);

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
        $data = new Data([
            0        => 0,
            1        => 1,
            'string' => 'test',
            2        => [
                1,
            ],
            'nested' => [
                '0',
                1,
            ],
        ]);

        isSame(0, $data->get(0));
        isSame(1, $data->find('2.0'));
        isSame('0', $data->find('nested.0'));
        isSame(0, $data['0']);
        isSame(1, $data[2][0]);
    }

    public function testPropsVisible()
    {
        $data = new Data($this->test);
        isTrue(count((array)$data) > 0);
    }

    public function testFunctions()
    {
        // General cases
        isClass(Data::class, json());
        isClass(Data::class, json(false));
        isClass(Data::class, json(null));
        isClass(Data::class, json(''));
        isClass(Data::class, json([]));
        isClass(Data::class, json('{}'));
        isClass(Data::class, json('{"test":42}'));
        isClass(Data::class, json($this->test));
        isClass(Data::class, json(json()));

        isSame('[]', '' . json());
        //isSame('[false]', '' . json(false));
        isSame('[]', '' . json(null));
        isSame('[]', '' . json(''));
        isSame('[]', '' . json([]));
        isSame('[]', '' . json('{}'));
        //isSame('{"test":42}', '' . json('{"test":42}'));
        isSame('[]', '' . json(json()));
        isSame(42, json('{"test":42}')->get('test'));

        $origObj = new JSON();
        is($origObj, json($origObj));


        // Similar functions
        $stdObj = new \stdClass();
        $stdObj->string = 'qwerty';

        isSame('qwerty', data($this->test)->get('string'));
        isSame('a:1:{s:4:"test";s:3:"123";}', (string)data(['test' => '123']));
        isClass(Data::class, data(data()));
        isClass(Data::class, data($stdObj));
        isSame('qwerty', data($stdObj)->get('string'));
        isSame('123', data('a:1:{s:4:"test";s:3:"123";}')->get('test'));


        isSame('qwerty', phpArray($this->test)->get('string'));
        isSame("<?php\n\nreturn array (\n  'test' => '123',\n);", (string)phpArray(['test' => '123']));
        isClass(PhpArray::class, phpArray(phpArray()));
        isClass(PhpArray::class, phpArray($stdObj));
        isSame('qwerty', phpArray($stdObj)->get('string'));
        isSame('localhost', phpArray('./tests/resource/data.inc')->get('host'));


        isSame('qwerty', ini($this->test)->get('string'));
        isSame('test = "123"', (string)ini(['test' => '123']));
        isClass(Ini::class, ini(ini()));
        isClass(Ini::class, ini($stdObj));
        isSame('qwerty', ini($stdObj)->get('string'));


        isSame('qwerty', yml($this->test)->get('string'));
        isSame("test: '123'\n", (string)yml(['test' => '123']));
        isClass(Yml::class, yml(yml()));
        isClass(Yml::class, yml($stdObj));
        isSame('qwerty', yml($stdObj)->get('string'));
    }
}
