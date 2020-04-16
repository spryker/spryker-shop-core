<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\MerchantSalesOrderWidget\Widget;

use ArrayObject;
use Spryker\Yves\Kernel\Widget\AbstractWidget;

/**
 * @method \SprykerShop\Yves\MerchantSalesOrderWidget\MerchantSalesOrderWidgetFactory getFactory()
 */
class MerchantOrderReferenceForItemsWidget extends AbstractWidget
{
    protected const PARAMETER_MERCHANT_ORDER_REFERENCES = 'merchantOrderReferences';

    /**
     * @param \ArrayObject $itemTransfers
     */
    public function __construct(ArrayObject $itemTransfers)
    {
        $this->addMerchantOrderReferencesParameter($itemTransfers->getArrayCopy());
    }

    /**
     * @return string
     */
    public static function getName(): string
    {
        return 'MerchantOrderReferenceForItemsWidget';
    }

    /**
     * @return string
     */
    public static function getTemplate(): string
    {
        return '@MerchantSalesOrderWidget/views/merchant-order-reference-for-items/merchant-order-reference-for-items.twig';
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer[] $itemTransfers
     *
     * @return $this
     */
    protected function addMerchantOrderReferencesParameter(array $itemTransfers)
    {
        $merchantOrderReferences = [];

        foreach ($itemTransfers as $itemTransfer) {
            if (!$itemTransfer->getMerchantOrderReference()) {
                continue;
            }

            $merchantOrderReferences[] = $itemTransfer->getMerchantOrderReference();
        }

        $this->addParameter(static::PARAMETER_MERCHANT_ORDER_REFERENCES, array_unique($merchantOrderReferences));

        return $this;
    }
}
