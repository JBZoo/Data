<?php
/**
 * JBZoo Data
 *
 * This file is part of the JBZoo CCK package.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @package    Data
 * @license    MIT
 * @copyright  Copyright (C) JBZoo.com, All rights reserved.
 * @link       https://github.com/JBZoo/Data
 * @author     Denis Smetannikov <denis@jbzoo.com>
 */

namespace JBZoo\PHPUnit;

use JBZoo\Data\PHPArray;

/**
 * Class PhpArrayDataTest
 *
 * @package JBZoo\Data
 */
class PhpArrayDataTest extends PHPUnit
{
    protected $testFile = './tests/resource/data.inc';

    public function testFile()
    {
        $data = new PHPArray($this->testFile);
        $dataValid = include $this->testFile;

        is($dataValid, $data->getArrayCopy());
    }

    public function testPropsVisible()
    {
        $data = new PHPArray($this->testFile);
        isTrue(count(get_object_vars($data)) > 0);
    }
}
