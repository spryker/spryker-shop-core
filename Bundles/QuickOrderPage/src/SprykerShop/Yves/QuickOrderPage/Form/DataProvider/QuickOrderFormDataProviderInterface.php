<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\QuickOrderPage\Form\DataProvider;

use Generated\Shared\Transfer\QuickOrderTransfer;

interface QuickOrderFormDataProviderInterface
{
    /**
     * @return array
     */
    public function getOptions(): array;

    /**
     * @param array<\Generated\Shared\Transfer\QuickOrderItemTransfer> $orderItems
     *
     * @return \Generated\Shared\Transfer\QuickOrderTransfer
     */
    public function getQuickOrderTransfer(array $orderItems = []): QuickOrderTransfer;

    /**
     * @param \Generated\Shared\Transfer\QuickOrderTransfer $quickOrder
     *
     * @return \Generated\Shared\Transfer\QuickOrderTransfer
     */
    public function appendEmptyQuickOrderItems(QuickOrderTransfer $quickOrder): QuickOrderTransfer;

    /**
     * @param array<array<string, mixed>> $formDataItems
     *
     * @return \Generated\Shared\Transfer\QuickOrderTransfer
     */
    public function mapFormDataToQuickOrderItems(array $formDataItems): QuickOrderTransfer;
}
