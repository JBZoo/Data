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

/**
 * Class DataCodeStyleTest
 *
 * @package JBZoo\PHPUnit
 */
class DataCodeStyleTest extends Codestyle
{
    protected $_packageName   = 'Data';
    protected $_packageAuthor = 'Denis Smetannikov <denis@jbzoo.com>';

    /**
     * {@inheritdoc}
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->_excludePaths[] = 'resource';
    }
}