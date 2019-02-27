<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\QuickOrderPage\Dependency\Service;

class QuickOrderPageToQuickOrderServiceBridge implements QuickOrderPageToQuickOrderServiceInterface
{
    /**
     * @var \Spryker\Service\QuickOrder\QuickOrderServiceInterface
     */
    protected $quickOrderService;

    /**
     * @param \Spryker\Service\QuickOrder\QuickOrderServiceInterface $quickOrderService
     */
    public function __construct($quickOrderService)
    {
        $this->quickOrderService = $quickOrderService;
    }

    /**
     * @param float $value
     *
     * @return float
     */
    public function round(float $value): float
    {
        return $this->quickOrderService->round($value);
    }
}
