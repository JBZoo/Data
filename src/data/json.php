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
    /**
     * If the returned object will be an associative array (default :true)
     * @var boolean
     */
    protected $assoc = true;

    /**
     * Class Constructor
     * @param string|array $data The data to read. Could be either an array or a json string
     */
    public function __construct($data = array())
    {
        // decode JSON string
        if (is_string($data)) {
            $data = json_decode($data, $this->assoc);
        }
        parent::__construct($data);
    }

    /**
     * Encode an array or an object in JSON format
     * @param array|object $data The data to encode
     * @return string
     */
    protected function _write($data)
    {
        return $this->_jsonEncode($data);
    }

    /**
     * Do the real json encoding adding human readability. Supports automatic indenting with tabs
     * @param array|object $in     The array or object to encode in json
     * @param int          $indent The indentation level. Adds $indent tabs to the string
     * @return string
     */
    public function _jsonEncode($in, $indent = 0)
    {
        $out = '';

        foreach ($in as $key => $value) {

            $out .= str_repeat("\t", $indent + 1);

            $out .= json_encode((string)$key) . ': ';

            if (is_object($value) || is_array($value)) {
                $out .= $this->_jsonEncode($value, $indent + 1);
            } else {
                $out .= json_encode($value);
            }

            $out .= ",\n";
        }

        if (!empty($out)) {
            $out = substr($out, 0, -2);
        }

        $out = " {\n" . $out;
        $out .= "\n" . str_repeat("\t", $indent) . "}";

        return $out;
    }
}
