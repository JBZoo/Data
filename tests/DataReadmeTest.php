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

/**
 * Class DataReadmeTest
 *
 * @package JBZoo\PHPUnit
 */
class DataReadmeTest extends AbstractReadmeTest
{
    protected $packageName = 'Data';

    /**
     * @inheritDoc
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->params['scrutinizer'] = true;
        $this->params['codefactor'] = true;
        $this->params['strict_types'] = true;
        $this->params['travis'] = false;
    }
}
