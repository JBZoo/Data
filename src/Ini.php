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
 * Class Ini
 * @package JBZoo\Data
 */
class Ini extends Data
{
    /**
     * Utility Method to unserialize the given data
     * @param string $string
     * @return mixed
     */
    protected function _decode($string)
    {
        return parse_ini_string($string, true, INI_SCANNER_NORMAL);
    }

    /**
     * @param mixed $data
     * @return string
     */
    protected function _encode($data)
    {
        return $this->_render($data, array());
    }

    /**
     * @param array $data
     * @param array $parent
     * @return string
     */
    protected function _render(array $data = array(), $parent = array())
    {
        $result = array();

        foreach ($data as $dataKey => $dataValue) {
            if (is_array($dataValue)) {
                if ($this->_isMulti($dataValue)) {
                    $sections = array_merge((array)$parent, (array)$dataKey);
                    $result[] = '';
                    $result[] = '[' . implode('.', $sections) . ']';
                    $result[] = $this->_render($dataValue, $sections);
                } else {
                    foreach ($dataValue as $key => $value) {
                        $result[] = $dataKey . '[' . $key . '] = "' . str_replace('"', '\"', $value) . '"';
                    }
                }

            } else {
                $result[] = $dataKey . ' = "' . str_replace('"', '\"', $dataValue) . '"';
            }
        }

        return implode(Data::LE, $result);
    }
}
