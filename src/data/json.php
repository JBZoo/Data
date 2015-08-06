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
 * Class JSON
 * @package SmetDenis\Data
 */
class JSON extends Base
{
    /**
     * Utility Method to unserialize the given data
     * @param string $string
     * @return mixed
     */
    protected function decode($string)
    {
        return json_decode($string, true);
    }

    /**
     * Utility Method to unserialize the given data
     * @param $data
     * @return mixed
     */
    protected function encode($data)
    {
        return $this->render($data);
    }

    /**
     * Do the real json encoding adding human readability. Supports automatic indenting with tabs
     * @param array|object $data   The array or object to encode in json
     * @param int          $indent The indentation level. Adds $indent tabs to the string
     * @return string
     */
    protected function render($data, $indent = 0)
    {
        $result = '';

        foreach ($data as $key => $value) {
            $result .= str_repeat('    ', $indent + 1);
            $result .= json_encode((string)$key) . ': ';

            $isComplex = is_object($value) || is_array($value);
            $result .= $isComplex ? $this->render($value, $indent + 1) : json_encode($value);
            $result .= ',' . Base::LE;
        }

        if (!empty($result)) {
            $result = substr($result, 0, -2);
        }

        $result = '{' . Base::LE . $result;
        $result .= Base::LE . str_repeat('    ', $indent) . '}';

        return $result;
    }
}
