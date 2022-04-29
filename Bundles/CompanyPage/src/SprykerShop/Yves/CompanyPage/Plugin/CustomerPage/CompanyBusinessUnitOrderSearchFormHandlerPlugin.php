<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CompanyPage\Plugin\CustomerPage;

use Generated\Shared\Transfer\OrderListTransfer;
use Spryker\Yves\Kernel\AbstractPlugin;
use SprykerShop\Yves\CustomerPageExtension\Dependency\Plugin\OrderSearchFormHandlerPluginInterface;

/**
 * @method \SprykerShop\Yves\CompanyPage\CompanyPageFactory getFactory()
 */
class CompanyBusinessUnitOrderSearchFormHandlerPlugin extends AbstractPlugin implements OrderSearchFormHandlerPluginInterface
{
    /**
     * {@inheritDoc}
     * - Handles company business unit field data transforming.
     *
     * @api
     *
     * @param array<string, mixed> $orderSearchFormData
     * @param \Generated\Shared\Transfer\OrderListTransfer $orderListTransfer
     *
     * @return \Generated\Shared\Transfer\OrderListTransfer
     */
    public function handle(
        array $orderSearchFormData,
        OrderListTransfer $orderListTransfer
    ): OrderListTransfer {
        return $this->getFactory()
            ->createOrderSearchFormHandler()
            ->handleOrderSearchFormSubmit($orderSearchFormData, $orderListTransfer);
    }
}
