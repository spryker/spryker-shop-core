<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\MerchantWidget\Plugin\QuickOrderPage;

use Generated\Shared\Transfer\QuickOrderItemTransfer;
use Spryker\Yves\Kernel\AbstractPlugin;
use SprykerShop\Yves\QuickOrderPageExtension\Dependency\Plugin\QuickOrderItemMapperPluginInterface;

/**
 * @method \SprykerShop\Yves\MerchantWidget\MerchantWidgetFactory getFactory()
 */
class MerchantQuickOrderItemMapperPlugin extends AbstractPlugin implements QuickOrderItemMapperPluginInterface
{
    /**
     * @uses \Generated\Shared\Transfer\QuickOrderItemTransfer::MERCHANT_REFERENCE
     *
     * @var string
     */
    protected const MERCHANT_REFERENCE = 'merchant_reference';

    /**
     * Specification:
     * - Maps merchant reference to QuickOrderItem transfer.
     *
     * @param \Generated\Shared\Transfer\QuickOrderItemTransfer $quickOrderItemTransfer
     * @param array<string, mixed> $data
     *
     * @return \Generated\Shared\Transfer\QuickOrderItemTransfer
     */
    public function map(QuickOrderItemTransfer $quickOrderItemTransfer, array $data): QuickOrderItemTransfer
    {
        if (isset($data[static::MERCHANT_REFERENCE])) {
            $quickOrderItemTransfer->setMerchantReference((string)$data[static::MERCHANT_REFERENCE]);
        }

        return $quickOrderItemTransfer;
    }
}
