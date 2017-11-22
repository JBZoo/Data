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

use JBZoo\Data\Ini;

/**
 * Class IniDataTest
 *
 * @package JBZoo\Data
 */
class IniDataTest extends PHPUnit
{
    protected $testFile = './tests/resource/data.ini';

    public function testFile()
    {
        $data = new Ini($this->testFile);
        $dataValid = openFile($this->testFile);

        is($dataValid, (string)$data);
    }

    public function testString()
    {
        $data = new Ini(openFile($this->testFile));
        $dataValid = openFile($this->testFile);

        is($dataValid, (string)$data);
    }

    public function testPropsVisible()
    {
        $data = new Ini(openFile($this->testFile));
        isTrue(count(get_object_vars($data)) > 0);
    }
}
