<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CheckoutPage\StrategyResolver;


trait MultiShipmentResolverTrait
{
    /**
     * @uses \Generated\Shared\Transfer\SpySalesOrderItemEntityTransfer
     *
     * @return bool
     */
    protected function isMultiShipmentModuleEnabled(): bool
    {
        return defined('\Generated\Shared\Transfer\ItemTransfer::SHIPMENT');
    }
}