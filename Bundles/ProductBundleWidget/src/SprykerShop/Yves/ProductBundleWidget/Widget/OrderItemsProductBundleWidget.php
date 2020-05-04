<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductBundleWidget\Widget;

use Spryker\Yves\Kernel\Widget\AbstractWidget;

/**
 * @method \SprykerShop\Yves\ProductBundleWidget\ProductBundleWidgetFactory getFactory()
 */
class OrderItemsProductBundleWidget extends AbstractWidget
{
    protected const PARAMETER_BUNDLE_ITEMS = 'bundleItems';

    /**
     * @param iterable|\Generated\Shared\Transfer\ItemTransfer[] $itemTransfers
     */
    public function __construct(iterable $itemTransfers)
    {
        $this->addBundleItemsParameter($itemTransfers);
    }

    /**
     * @return string
     */
    public static function getName(): string
    {
        return 'OrderItemsProductBundleWidget';
    }

    /**
     * @return string
     */
    public static function getTemplate(): string
    {
        return '@ProductBundleWidget/views/order-items-product-bundle-widget/order-items-product-bundle-widget.twig';
    }

    /**
     * @param iterable|\Generated\Shared\Transfer\ItemTransfer[] $itemTransfers
     *
     * @return void
     */
    protected function addBundleItemsParameter(iterable $itemTransfers): void
    {
        $this->addParameter(
            static::PARAMETER_BUNDLE_ITEMS,
            $this->getFactory()->createItemExtractor()->extractBundleItems($itemTransfers)
        );
    }
}
