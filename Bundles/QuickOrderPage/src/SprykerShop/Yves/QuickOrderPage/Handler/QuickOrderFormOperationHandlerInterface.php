<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\QuickOrderPage\Handler;

use SprykerShop\Yves\QuickOrderPage\Form\QuickOrderData;
use Symfony\Component\Form\FormInterface;

interface QuickOrderFormOperationHandlerInterface
{
    /**
     * @param \SprykerShop\Yves\QuickOrderPage\Form\QuickOrderData $quickOrder
     *
     * @return void
     */
    public function addToCart(QuickOrderData $quickOrder): void;

    /**
     * @param \SprykerShop\Yves\QuickOrderPage\Form\QuickOrderData $quickOrder
     *
     * @return void
     */
    public function createOrder(QuickOrderData $quickOrder): void;

    /**
     * @param \SprykerShop\Yves\QuickOrderPage\Form\QuickOrderData $quickOrder
     * @param \Symfony\Component\Form\FormInterface $quickOrderForm
     */
    public function verifyTextOrder(QuickOrderData $quickOrder, FormInterface $quickOrderForm): void;
}
