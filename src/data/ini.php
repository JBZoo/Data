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
 * Class Ini
 * @package SmetDenis\Data
 */
class Ini extends Base
{
    /**
     * Utility Method to unserialize the given data
     * @param string $string
     * @return mixed
     */
    protected function decode($string)
    {
        return parse_ini_string($string, true, INI_SCANNER_NORMAL);
    }

    /**
     * @param mixed $data
     * @return string
     */
    protected function encode($data)
    {
        return $this->render($data, array());
    }

    /**
     * @param array $data
     * @param array $parent
     * @return string
     */
    protected function render(array $data = array(), $parent = array())
    {
        $result = array();

        foreach ($data as $dataKey => $dataValue) {
            if (is_array($dataValue)) {
                if ($this->isMulti($dataValue)) {
                    $sections = array_merge((array)$parent, (array)$dataKey);
                    $result[] = '';
                    $result[] = '[' . implode('.', $sections) . ']';
                    $result[] = $this->render($dataValue, $sections);
                } else {
                    foreach ($dataValue as $key => $value) {
                        $result[] = $dataKey . '[' . $key . '] = "' . str_replace('"', '\"', $value) . '"';
                    }
                }

            } else {
                $result[] = $dataKey . ' = "' . str_replace('"', '\"', $dataValue) . '"';
            }
        }

        return implode(Base::LE, $result);
    }
}
