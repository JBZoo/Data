<?php
/**
 * SimpleTypes
 *
 * Copyright (c) 2015, Denis Smetannikov <denis@jbzoo.com>.
 *
 * @package   SimpleTypes
 * @author    Denis Smetannikov <denis@jbzoo.com>
 * @copyright 2015 Denis Smetannikov <denis@jbzoo.com>
 * @link      http://github.com/smetdenis/simpletypes
 */

namespace SmetDenis\Data;

/**
 * Class LETest
 * @package SmetDenis\Data
 */
class SimpleTest extends PHPUnit
{


    public function testCreate()
    {
        $data = new Data(array());
        $this->assertInstanceOf('\IteratorAggregate', $data);
        $this->assertInstanceOf('\ArrayAccess', $data);
        $this->assertInstanceOf('\Serializable', $data);
        $this->assertInstanceOf('\Countable', $data);
    }


}
