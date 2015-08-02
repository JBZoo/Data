<?php
/**
 * SimpleTypes
 *
 * Copyright (c) 2015, Denis Smetannikov <denis@jbzoo.com>.
 *
 * @package   SimpleTypes
 * @author    Denis Smetannikov <denis@jbzoo.com>
 * @copyright 2015 Denis Smetannikov <denis@jbzoo.com>
 * @link      http://github.com/smetdenis/simpletypes
 */

namespace SmetDenis\Data;

/**
 * Class PHPUnit
 * @package SmetDenis\Data
 */
class PHPUnit extends \PHPUnit_Framework_TestCase
{
    protected $namespace = '\\SmetDenis\\SimpleTypes\\';

    protected static $times = array();
    protected static $memories = array();

    protected $excludeList = array(
        '.',
        '..',
        '.idea',
        '.git',
        'build',
        'vendor',
        'reports',
        'composer.phar',
        'composer.lock',
    );

    /**
     * @param $testList
     */
    public function batchEquals($testList)
    {
        foreach ($testList as $test) {
            $this->assertEquals($test[0], $test[1]);
        }
    }

    /**
     * Start profiler
     */
    public function startProfiler()
    {
        array_push(self::$times, microtime(true));
        array_push(self::$memories, memory_get_usage(false));
    }

    /**
     * @param int $count
     * @return array
     */
    public function markProfiler($count = 1, $measure = null)
    {
        $time   = microtime(true);
        $memory = memory_get_usage(false);

        $timeDiff   = $time - end(self::$times);
        $memoryDiff = $memory - end(self::$memories);

        array_push(self::$times, $time);
        array_push(self::$memories, $memory);

        // build report
        $count  = (int)abs($count);
        $result = array(
            'count'      => $count,
            'time'       => $timeDiff,
            'memory'     => $memoryDiff,
            'timeOne'    => $timeDiff / $count,
            'memoryOne'  => $memoryDiff / $count,
            'timeF'      => number_format($timeDiff * 1000, 2, '.', ' ') . ' ms',
            'memoryF'    => number_format($memoryDiff / 1024, 2, '.', ' ') . ' KB',
            'timeOneF'   => number_format($timeDiff * 1000 / $count, 2, '.', ' ') . ' ms',
            'memoryOneF' => number_format($memoryDiff / 1024 / $count, 2, '.', ' ') . ' KB',
        );

        if ($measure && isset($result[$measure])) {
            return $result[$measure];
        }

        return $result;
    }

    protected function getFileList($dir, $filter = null, &$results = array())
    {
        $files = scandir($dir);

        foreach ($files as $key => $value) {
            $path = $dir . DIRECTORY_SEPARATOR . $value;

            if (!is_dir($path) && !in_array($value, $this->excludeList, true)) {

                if ($filter) {
                    if (preg_match($filter, $path)) {
                        $results[] = $path;
                    }
                } else {
                    $results[] = $path;
                }

            } elseif (is_dir($path) && !in_array($value, $this->excludeList, true)) {
                $this->getFileList($path, $filter, $results);
            }
        }

        return $results;
    }

    protected function openFile($path)
    {
        $contents = null;

        if ($realPath = realpath($path)) {
            $handle   = fopen($path, "rb");
            $contents = fread($handle, filesize($path));
            fclose($handle);
        }

        return $contents;
    }
}
