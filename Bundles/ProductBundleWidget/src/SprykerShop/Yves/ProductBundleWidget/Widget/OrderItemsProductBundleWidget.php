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
    protected const PARAMETER_PRODUCT_BUNDLES = 'productBundles';

    /**
     * @param iterable|\Generated\Shared\Transfer\ItemTransfer[] $itemTransfers
     */
    public function __construct(iterable $itemTransfers)
    {
        $this->addProductBundlesParameter($itemTransfers);
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
        return '@ProductBundleWidget/views/ordered-product-bundles-list/ordered-product-bundles-list.twig';
    }

    /**
     * @param iterable|\Generated\Shared\Transfer\ItemTransfer[] $itemTransfers
     *
     * @return void
     */
    protected function addProductBundlesParameter(iterable $itemTransfers): void
    {
        $this->addParameter(
            static::PARAMETER_PRODUCT_BUNDLES,
            $this->getFactory()->createItemExtractor()->extractBundleItems($itemTransfers)
        );
    }
}
