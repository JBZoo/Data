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

namespace SmetDenis\Data;

/**
 * Class JSONData
 * @package SmetDenis\Data
 */
class Data extends Base
{
    /**
     * Utility Method to serialize the given data
     * @param mixed $data The data to serialize
     * @return string The serialized data
     */
    protected function write($data)
    {
        return serialize($data);
    }

}
