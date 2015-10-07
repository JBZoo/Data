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
 * Class phpArrayDataTest
 * @package JBZoo\Data
 */
class phpArrayDataTest extends PHPUnit
{

    protected $testFile = './tests/resource/data.inc';

    public function testFile()
    {
        $data      = new PhpArray($this->testFile);
        $dataValid = $this->openFile($this->testFile);

        self::assertEquals($dataValid, (string)$data);
    }
}
