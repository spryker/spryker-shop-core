<?php
/**
 * Created by PhpStorm.
 * User: kravchenko
 * Date: 2019-03-16
 * Time: 09:17
 */

namespace SprykerShop\Yves\QuickOrderPage\Dependency\Service;

interface QuickOrderPageToUtilQuantityServiceInterface
{
    /**
     * @param float $quantity
     *
     * @return float
     */
    public function roundQuantity(float $quantity): float;
}
