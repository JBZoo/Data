<?php

/**
 * JBZoo Toolbox - Data
 *
 * This file is part of the JBZoo Toolbox project.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @package    Data
 * @license    MIT
 * @copyright  Copyright (C) JBZoo.com, All rights reserved.
 * @link       https://github.com/JBZoo/Data
 */

declare(strict_types=1);

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
        isTrue(count((array)$data) > 0);
    }
}
