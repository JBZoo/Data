<?php

/**
 * JBZoo Toolbox - Data.
 *
 * This file is part of the JBZoo Toolbox project.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @license    MIT
 * @copyright  Copyright (C) JBZoo.com, All rights reserved.
 * @see        https://github.com/JBZoo/Data
 */

declare(strict_types=1);

use JBZoo\Data\Data;

/**
 * @BeforeMethods({"init"})
 * @Revs(1000000)
 * @Iterations(3)
 */
class GetUndefinedValue
{
    protected array $array = [];

    /** @var Data */
    protected $data = [];

    /** @var null|ArrayObject */
    protected $arrObj;

    /** @var null|ArrayObjectExt */
    protected $arrObjExt;

    public function init(): void
    {
        $this->array     = DataFixture::createRandomArray();
        $this->data      = new Data($this->array);
        $this->arrObj    = new ArrayObject($this->array);
        $this->arrObjExt = new ArrayObjectExt($this->array);
    }

    /**
     * @Groups({"Native", "Array", "Undefined"})
     */
    public function benchArrayRegularMuted()
    {
        return @$this->array['undefined'];
    }

    /**
     * @Groups({"Native", "Array", "Undefined"})
     */
    public function benchArrayIsset()
    {
        return $this->array['undefined'] ?? null;
    }

    /**
     * @Groups({"Data", "Undefined"})
     */
    public function benchDataGet()
    {
        return $this->data->get('undefined');
    }

    /**
     * @Groups({"Data", "Undefined"})
     */
    public function benchDataArrow()
    {
        return $this->data->undefined;
    }

    /**
     * @Groups({"Data", "Undefined"})
     */
    public function benchDataArray()
    {
        return $this->data['undefined'];
    }

    /**
     * @Groups({"Data", "Undefined"})
     */
    public function benchDataFind()
    {
        return $this->data->find('undefined');
    }

    /**
     * @Groups({"Data", "Undefined"})
     */
    public function benchDataFindInner()
    {
        return $this->data->find('undefined.inner');
    }

    /**
     * @Groups({"Data", "Undefined"})
     */
    public function benchDataOffsetGet()
    {
        return $this->data->offsetGet('undefined');
    }
}
