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
 * Class YmlDataTest
 * @package JBZoo\Data
 */
class YmlDataTest extends PHPUnit
{

    protected $testFile = './tests/resource/data.yml';

    public function testFile()
    {
        $data      = new Yml($this->testFile);
        $dataValid = $this->openFile($this->testFile);

        self::assertEquals($dataValid, (string)$data);
    }

    public function testString()
    {
        $data      = new Yml($this->openFile($this->testFile));
        $dataValid = $this->openFile($this->testFile);

        self::assertEquals($dataValid, (string)$data);
    }
}
