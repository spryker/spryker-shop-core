<?php
/**
 * Created by PhpStorm.
 * User: kravchenko
 * Date: 2019-04-04
 * Time: 11:02
 */

namespace SprykerShop\Yves\CustomerReorderWidget\Dependency\Service;

interface CustomerReorderWidgetToUtilQuantityServiceInterface
{
    /**
     * @param float $firstQuantity
     * @param float $secondQuantity
     *
     * @return bool
     */
    public function isQuantityEqual(float $firstQuantity, float $secondQuantity): bool;
}
