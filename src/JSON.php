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
     * @param int          $indent The indentation level. Adds $indent tabs to the string
     * @return string
     */
    protected function _render($data, $indent = 0)
    {
        if (defined('JSON_PRETTY_PRINT')) {
            return json_encode($data, JSON_PRETTY_PRINT);
        }

        if (count($data) == 0) {
            return '[]';
        }

        $result = '';

        foreach ($data as $key => $value) {
            $result .= str_repeat('    ', $indent + 1);
            $result .= json_encode((string)$key) . ': ';

            $isComplex = is_object($value) || is_array($value);
            $result .= $isComplex ? $this->_render($value, $indent + 1) : json_encode($value);
            $result .= ',' . Data::LE;
        }

        if (!empty($result)) {
            $result = substr($result, 0, -2);
        }

        $result = '{' . Data::LE . $result;
        $result .= Data::LE . str_repeat('    ', $indent) . '}';

        return $result;
    }
}
