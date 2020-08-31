<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductConfigurationWidget\Widget;

use Generated\Shared\Transfer\ProductViewTransfer;
use Spryker\Yves\Kernel\Widget\AbstractWidget;

/**
 * @method \SprykerShop\Yves\ProductConfigurationWidget\ProductConfigurationWidgetFactory getFactory()
 * @method \SprykerShop\Yves\ProductConfigurationWidget\ProductConfigurationWidgetConfig getConfig()
 */
class ProductConfiguratorProductDetailPageButtonWidget extends AbstractWidget
{
    /**
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
     */
    public function __construct(ProductViewTransfer $productViewTransfer)
    {
        $productConfigurationInstanceTransfer = $productViewTransfer->getProductConfigurationInstance();

        if (!$productConfigurationInstanceTransfer) {
            return;
        }

        $this->addParameter(
            'productConfigurationRouteName',
            $this->getConfig()->getProductConfigurationGateRequestRoute()
        );

        $this->addParameter('sku', $productViewTransfer->getSku());
        $this->addParameter('quantity', $productViewTransfer->getQuantity());
        $this->addParameter('sourceType', $this->getConfig()->getPdpSourceType());
    }

    /**
     * @return string
     */
    public static function getName(): string
    {
        return 'ProductConfiguratorProductDetailPageButtonWidget';
    }

    /**
     * @return string
     */
    public static function getTemplate(): string
    {
        return '@ProductConfigurationWidget/views/product-configuration/product-view-product-configuration-button.twig';
    }
}
