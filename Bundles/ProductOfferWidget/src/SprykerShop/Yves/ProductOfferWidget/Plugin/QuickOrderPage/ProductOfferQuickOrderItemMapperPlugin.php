<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductOfferWidget\Plugin\QuickOrderPage;

use Generated\Shared\Transfer\QuickOrderItemTransfer;
use Spryker\Yves\Kernel\AbstractPlugin;
use SprykerShop\Yves\QuickOrderPageExtension\Dependency\Plugin\QuickOrderItemMapperPluginInterface;

/**
 * @method \SprykerShop\Yves\ProductOfferWidget\ProductOfferWidgetFactory getFactory()
 */
class ProductOfferQuickOrderItemMapperPlugin extends AbstractPlugin implements QuickOrderItemMapperPluginInterface
{
    /**
     * @uses \SprykerShop\Yves\QuickOrderPage\Controller\QuickOrderController::PARAM_QUICK_ORDER_FORM
     *
     * @var string
     */
    protected const NAME_QUICK_ORDER_FORM = 'quick_order_form';

    /**
     * @uses \SprykerShop\Yves\QuickOrderPage\Form\QuickOrderForm::FIELD_ITEMS
     *
     * @var string
     */
    protected const QUICK_ORDER_FORM_FIELD_ITEMS = 'items';

    /**
     * @uses \Generated\Shared\Transfer\QuickOrderItemTransfer::PRODUCT_OFFER_REFERENCE
     *
     * @var string
     */
    protected const PRODUCT_OFFER_REFERENCE = 'product_offer_reference';

    /**
     * Specification:
     * - Maps product offer reference to QuickOrderItem transfer.
     *
     * @param \Generated\Shared\Transfer\QuickOrderItemTransfer $quickOrderItemTransfer
     * @param array<string, mixed> $data
     *
     * @return \Generated\Shared\Transfer\QuickOrderItemTransfer
     */
    public function map(QuickOrderItemTransfer $quickOrderItemTransfer, array $data): QuickOrderItemTransfer
    {
        if (!isset($data[static::NAME_QUICK_ORDER_FORM][static::QUICK_ORDER_FORM_FIELD_ITEMS])) {
            return $quickOrderItemTransfer;
        }

        foreach ($data[static::NAME_QUICK_ORDER_FORM][static::QUICK_ORDER_FORM_FIELD_ITEMS] as $item) {
            if ($item[static::PRODUCT_OFFER_REFERENCE]) {
                $quickOrderItemTransfer->setProductOfferReference((string)$item[static::PRODUCT_OFFER_REFERENCE]);
            }
        }

        return $quickOrderItemTransfer;
    }
}
