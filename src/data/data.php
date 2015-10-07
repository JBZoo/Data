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

namespace JBZoo\Data;

/**
 * Class Data
 * @package JBZoo\Data
 */
class Data extends Base
{
    /**
     * Utility Method to unserialize the given data
     * @param string $string
     * @return mixed
     */
    protected function decode($string)
    {
        return unserialize($string);
    }

    /**
     * Utility Method to serialize the given data
     * @param mixed $data The data to serialize
     * @return string The serialized data
     */
    protected function encode($data)
    {
        return serialize($data);
    }
}
