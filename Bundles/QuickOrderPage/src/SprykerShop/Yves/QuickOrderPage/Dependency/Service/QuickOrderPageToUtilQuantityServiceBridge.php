<?php
/**
 * Created by PhpStorm.
 * User: kravchenko
 * Date: 2019-03-16
 * Time: 09:14
 */

namespace SprykerShop\Yves\QuickOrderPage\Dependency\Service;


class QuickOrderPageToUtilQuantityServiceBridge implements QuickOrderPageToUtilQuantityServiceInterface
{
    /**
     * @var \Spryker\Service\UtilQuantity\UtilQuantityServiceInterface
     */
    protected $utilQuantityService;

    /**
     * QuickOrderPageToUtilQuantityServiceBridge constructor.
     * @param \Spryker\Service\UtilQuantity\UtilQuantityServiceInterface $utilQuantityService
     */
    public function __construct($utilQuantityService)
    {
        $this->utilQuantityService = $utilQuantityService;
    }

    /**
     * @param float $quantity
     *
     * @return float
     */
    public function roundQuantity(float $quantity): float
    {
        return $this->utilQuantityService->roundQuantity($quantity);
    }
}
