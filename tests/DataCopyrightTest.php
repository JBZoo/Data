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
 * @author     Denis Smetannikov <denis@jbzoo.com>
 */

namespace JBZoo\PHPUnit;

/**
 * Class PHPUnitCopyrightTest
 *
 * @package JBZoo\PHPUnit
 */
class DataCopyrightTest extends AbstractCopyrightTest
{
    /**
     * @var string
     */
    protected $packageName = 'Data';

    protected function setUp(): void
    {
        $this->excludePaths[] = 'resource';

        parent::setUp();
    }
}
