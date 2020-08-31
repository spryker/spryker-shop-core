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
class ProductConfiguratorProductViewButtonWidget extends AbstractWidget
{
    protected const PARAMETER_PRODUCT_CONFIGURATION_ROUTE_NAME = 'productConfigurationRouteName';
    protected const PARAMETER_SKU = 'sku';
    protected const PARAMETER_SOURCE_TYPE = 'sourceType';
    protected const PARAMETER_QUANTITY = 'quantity';

    /**
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
     */
    public function __construct(ProductViewTransfer $productViewTransfer)
    {
        if (!$productViewTransfer->getProductConfigurationInstance()) {
            return;
        }

        // TODO: csrf forms protection

        $this->addProductConfigurationRouteNameParameter();
        $this->addSourceTypeParameter();
        $this->addSkuParameter($productViewTransfer);
        $this->addQuantityParameter($productViewTransfer);
    }

    /**
     * @return string
     */
    public static function getName(): string
    {
        return 'ProductConfiguratorProductViewButtonWidget';
    }

    /**
     * @return string
     */
    public static function getTemplate(): string
    {
        return '@ProductConfigurationWidget/views/product-configurator-product-view-button-widget/product-configurator-product-view-button-widget.twig';
    }

    /**
     * @return void
     */
    protected function addProductConfigurationRouteNameParameter(): void
    {
        $this->addParameter(
            static::PARAMETER_PRODUCT_CONFIGURATION_ROUTE_NAME,
            $this->getConfig()->getProductConfigurationGatewayRequestRoute()
        );
    }

    /**
     * @return void
     */
    protected function addSourceTypeParameter(): void
    {
        $this->addParameter(static::PARAMETER_SOURCE_TYPE, $this->getConfig()->getPdpSourceType());
    }

    /**
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
     *
     * @return void
     */
    protected function addSkuParameter(ProductViewTransfer $productViewTransfer): void
    {
        $this->addParameter(static::PARAMETER_SKU, $productViewTransfer->getSku());
    }

    /**
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
     *
     * @return void
     */
    protected function addQuantityParameter(ProductViewTransfer $productViewTransfer): void
    {
        $this->addParameter(static::PARAMETER_QUANTITY, $productViewTransfer->getQuantity());
    }
}
