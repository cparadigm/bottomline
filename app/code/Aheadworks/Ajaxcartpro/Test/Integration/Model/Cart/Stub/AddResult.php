<?php
/**
* Copyright 2016 aheadWorks. All rights reserved.
* See LICENSE.txt for license details.
*/

namespace Aheadworks\Ajaxcartpro\Test\Integration\Model\Cart\Stub;

// @codingStandardsIgnoreFile

/**
 * Class AddResult
 * @package Aheadworks\Ajaxcartpro\Test\Integration\Model\Cart\Stub
 */
class AddResult extends \Aheadworks\Ajaxcartpro\Model\Cart\AddResult
{
    /**
     * @var bool|null
     */
    private $getResult;

    /**
     * @param $getResult
     */
    public function setGetResult($getResult)
    {
        $this->getResult = $getResult;
    }

    /**
     * @return bool|null
     */
    public function get()
    {
        return !$this->getResult === null
            ? parent::get()
            : $this->getResult;
    }
}
