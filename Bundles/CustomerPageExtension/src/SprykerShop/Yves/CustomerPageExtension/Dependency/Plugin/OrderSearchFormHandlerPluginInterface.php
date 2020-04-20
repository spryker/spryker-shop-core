<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CustomerPageExtension\Dependency\Plugin;

use Generated\Shared\Transfer\OrderListTransfer;

/**
 * Provides form handling capabilities.
 *
 * Use this plugin for handling OrderSearchForm after submitting.
 */
interface OrderSearchFormHandlerPluginInterface
{
    /**
     * Specification:
     * - Handles OrderSearchForm submit.
     *
     * @api
     *
     * @param array $orderSearchFormData
     * @param \Generated\Shared\Transfer\OrderListTransfer $orderListTransfer
     *
     * @return \Generated\Shared\Transfer\OrderListTransfer
     */
    public function handle(array $orderSearchFormData, OrderListTransfer $orderListTransfer): OrderListTransfer;
}
