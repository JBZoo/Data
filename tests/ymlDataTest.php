<?php
/**
 * Data
 *
 * Copyright (c) 2015, Denis Smetannikov <denis@jbzoo.com>.
 *
 * @package   Data
 * @author    Denis Smetannikov <denis@jbzoo.com>
 * @copyright 2015 Denis Smetannikov <denis@jbzoo.com>
 * @link      http://github.com/SmetDenis/Data
 */

use SmetDenis\Data\Yml;

/**
 * Class YmlDataTest
 * @package SmetDenis\Data
 */
class YmlDataTest extends PHPUnit
{

    protected $testFile = './tests/resource/data.yml';

    public function testFile()
    {
        $data      = new Yml($this->testFile);
        $dataValid = $this->openFile($this->testFile);

        self::assertEquals($dataValid, (string)$data);
    }

    public function testString()
    {
        $data      = new Yml($this->openFile($this->testFile));
        $dataValid = $this->openFile($this->testFile);

        self::assertEquals($dataValid, (string)$data);
    }
}
