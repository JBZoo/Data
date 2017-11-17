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
 * Class JSON
 * @package JBZoo\Data
 */
class JSON extends Data
{
    /**
     * Utility Method to unserialize the given data
     * @param string $string
     * @return mixed
     */
    protected function _decode($string)
    {
        return json_decode($string, true);
    }

    /**
     * Utility Method to unserialize the given data
     * @param $data
     * @return string
     */
    protected function _encode($data)
    {
        return $this->_render($data);
    }

    /**
     * Do the real json encoding adding human readability. Supports automatic indenting with tabs
     * @param array|object $data   The array or object to encode in json
     * @return string
     */
    protected function _render($data)
    {
        return json_encode($data, JSON_PRETTY_PRINT);
    }
}
