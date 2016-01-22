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
 * Class PHPArray
 * @package JBZoo\Data
 */
class PHPArray extends Data
{
    const TAB = '    ';

    /**
     * Class constructor
     * @param array|string $data The data array
     */
    public function __construct($data = array())
    {
        if ($data && is_string($data) && file_exists($data)) {
            $data = $this->_decode($data);
        }

        parent::__construct($data ? (array)$data : array());
    }

    /**
     * Utility Method to unserialize the given data
     * @param string $string
     * @return mixed
     */
    protected function _decode($string)
    {
        return include $string;
    }

    /**
     * Utility Method to serialize the given data
     * @param mixed $data The data to serialize
     * @return string The serialized data
     */
    protected function _encode($data)
    {
        $data = array(
            '<?php',
            '',
            'return ' . $this->_render($data, 0) . ';',
        );

        return implode(Data::LE, $data);
    }

    /**
     * @param array $array
     * @param int   $depth
     * @return string
     */
    protected function _render($array, $depth = 0)
    {
        $data = (array)$array;

        $string = 'array(' . Data::LE;

        $depth++;
        foreach ($data as $key => $val) {
            $string .= $this->_getIndent($depth) . $this->_quoteWrap($key) . ' => ';

            if (is_array($val) || is_object($val)) {
                $string .= $this->_render($val, $depth) . ',' . Data::LE;
            } else {
                $string .= $this->_quoteWrap($val) . ',' . Data::LE;
            }
        }

        $depth--;
        $string .= $this->_getIndent($depth) . ')';

        return $string;
    }

    /**
     * @param $depth
     * @return string
     */
    protected function _getIndent($depth)
    {
        return str_repeat(self::TAB, $depth);
    }

    /**
     * @param $var
     * @return string
     */
    protected function _quoteWrap($var)
    {
        $type = strtolower(gettype($var));

        switch ($type) {
            case 'string':
                return "'" . str_replace("'", "\\'", $var) . "'";

            case 'null':
                return 'null';

            case 'boolean':
                return $var ? 'true' : 'false';

            //TODO: handle other variable types.. ( objects? )
            case 'integer':
            case 'double':
        }

        return $var;
    }
}
