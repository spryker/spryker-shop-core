<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductQuantityRestrictionWidget\Dependency\Service;

class ProductQuantityRestrictionWidgetToProductQuantityServiceBridge implements ProductQuantityRestrictionWidgetToProductQuantityServiceInterface
{
    /**
     * @var \Spryker\Service\ProductQuantity\ProductQuantityServiceInterface
     */
    protected $productQuantityService;

    /**
     * @param \Spryker\Service\ProductQuantity\ProductQuantityServiceInterface $productQuantityService
     */
    public function __construct($productQuantityService)
    {
        $this->productQuantityService = $productQuantityService;
    }

    /**
     * @return float
     */
    public function getDefaultMinimumQuantity(): float
    {
        return $this->productQuantityService->getDefaultMinimumQuantity();
    }

    /**
     * @return float
     */
    public function getDefaultInterval(): float
    {
        return $this->productQuantityService->getDefaultInterval();
    }
}
