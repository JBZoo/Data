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
class GetValueInner
{
    /** @var array */
    protected $array = [];

    /** @var Data */
    protected $data = [];

    /** @var null|ArrayObject */
    protected $arrObj;

    /** @var null|ArrayObjectExt */
    protected $arrObjExt;

    public function init(): void
    {
        $this->array     = Fixture::createRandomArray();
        $this->data      = new Data($this->array);
        $this->arrObj    = new ArrayObject($this->array);
        $this->arrObjExt = new ArrayObjectExt($this->array);
    }

    /**
     * @Groups({"Native", "Array"})
     */
    public function benchArrayRegular()
    {
        return $this->array['inner']['inner']['prop'];
    }

    /**
     * @Groups({"Native", "Array"})
     */
    public function benchArrayRegularMuted()
    {
        return @$this->array['inner']['inner']['prop'];
    }

    /**
     * @Groups({"Native", "Array"})
     */
    public function benchArrayIsset()
    {
        return $this->array['inner']['inner']['prop'] ?? null;
    }

    /**
     * @Groups({"Native", "ArrayObject"})
     */
    public function benchArrayObjectArray()
    {
        return $this->arrObj['inner']['inner']['prop'];
    }

    /**
     * @Groups({"Native", "ArrayObject", "Extended"})
     */
    public function benchArrayObjectArrayExt()
    {
        return $this->arrObjExt['inner']['inner']['prop'];
    }

    /**
     * @Groups({"Data"})
     */
    public function benchDataFind()
    {
        return $this->data->find('inner.inner.prop');
    }
}
