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

use SmetDenis\Data\Ini;

/**
 * Class iniDataTest
 * @package SmetDenis\Data
 */
class iniDataTest extends PHPUnit
{

    protected $testFile = './tests/resource/data.ini';

    public function testFile()
    {
        $data      = new Ini($this->testFile);
        $dataValid = $this->openFile($this->testFile);

        self::assertEquals($dataValid, (string)$data);
    }

    public function testString()
    {
        $data      = new Ini($this->openFile($this->testFile));
        $dataValid = $this->openFile($this->testFile);

        self::assertEquals($dataValid, (string)$data);
    }
}
