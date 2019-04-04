<?php
/**
 * Created by PhpStorm.
 * User: kravchenko
 * Date: 2019-04-04
 * Time: 10:57
 */

namespace SprykerShop\Yves\CustomerReorderWidget\Dependency\Service;


class CustomerReorderWidgetToUtilQuantityServiceBridge implements CustomerReorderWidgetToUtilQuantityServiceInterface
{
    /**
     * @var \Spryker\Service\UtilQuantity\UtilQuantityServiceInterface
     */
    protected $utilQuantityService;

    /**
     * @param \Spryker\Service\UtilQuantity\UtilQuantityServiceInterface $utilQuantityService
     */
    public function __construct($utilQuantityService)
    {
        $this->utilQuantityService = $utilQuantityService;
    }

    /**
     * @param float $firstQuantity
     * @param float $secondQuantity
     *
     * @return bool
     */
    public function isQuantityEqual(float $firstQuantity, float $secondQuantity): bool
    {
        return $this->utilQuantityService->isQuantityEqual($firstQuantity, $secondQuantity);
    }
}
