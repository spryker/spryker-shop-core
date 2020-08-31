<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductConfigurationWidget\Widget;

use Generated\Shared\Transfer\ItemTransfer;
use Spryker\Yves\Kernel\Widget\AbstractWidget;

/**
 * @method \SprykerShop\Yves\ProductConfigurationWidget\ProductConfigurationWidgetFactory getFactory()
 * @method \SprykerShop\Yves\ProductConfigurationWidget\ProductConfigurationWidgetConfig getConfig()
 */
class ProductConfiguratorCartPageButtonWidget extends AbstractWidget
{
    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param int $quantity
     */
    public function __construct(ItemTransfer $itemTransfer, int $quantity)
    {
        $productConfigurationInstanceTransfer = $itemTransfer->getProductConfigurationInstance();

        if (!$productConfigurationInstanceTransfer) {
            return;
        }

        $this->addParameter(
            'productConfigurationRouteName',
            $this->getConfig()->getProductConfigurationGatewayRequestRoute()
        );

        $this->addParameter('itemGroupKey', $itemTransfer->getGroupKey());
        $this->addParameter('sourceType', $this->getConfig()->getCartSourceType());
        $this->addParameter('quantity', $quantity);
    }

    /**
     * @return string
     */
    public static function getName(): string
    {
        return 'ProductConfiguratorCartPageButtonWidget';
    }

    /**
     * @return string
     */
    public static function getTemplate(): string
    {
        return '@ProductConfigurationWidget/views/product-configuration/cart-product-configuration-button.twig';
    }
}
