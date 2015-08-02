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
class JSONData extends Data
{
    const LE = "\n";

    /**
     * Class Constructor
     * @param string|array $data The data to read. Could be either an array or a json string
     */
    public function __construct($data = array())
    {
        // decode JSON string
        if (is_string($data)) {
            $data = json_decode($data, true);
        }

        parent::__construct($data);
    }

    /**
     * Encode an array or an object in JSON format
     * @param array|object $data The data to encode
     * @return string
     */
    protected function write($data)
    {
        return $this->jsonEncode($data);
    }

    /**
     * Do the real json encoding adding human readability. Supports automatic indenting with tabs
     * @param array|object $data   The array or object to encode in json
     * @param int          $indent The indentation level. Adds $indent tabs to the string
     * @return string
     */
    public function jsonEncode($data, $indent = 0)
    {
        $out = '';

        foreach ($data as $key => $value) {

            $out .= str_repeat('    ', $indent + 1);

            $out .= json_encode((string)$key) . ': ';

            if (is_object($value) || is_array($value)) {
                $out .= $this->jsonEncode($value, $indent + 1);
            } else {
                $out .= json_encode($value);
            }

            $out .= ',' . self::LE;
        }

        if (!empty($out)) {
            $out = substr($out, 0, -2);
        }

        $out = '{' . self::LE . $out;
        $out .= self::LE . str_repeat("\t", $indent) . '}';

        return $out;
    }
}
