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
 */
class ProductConfiguratorCartItemDisplayWidget extends AbstractWidget
{
    protected const PARAMETER_IS_VISIBLE = 'isVisible';
    protected const PARAMETER_PRODUCT_CONFIGURATION_INSTANCE = 'productConfigurationInstance';
    protected const PARAMETER_PRODUCT_CONFIGURATION_TEMPLATE = 'productConfigurationTemplate';

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     */
    public function __construct(ItemTransfer $itemTransfer)
    {
        $this->addIsVisibleParameter($itemTransfer);

        if (!$itemTransfer->getProductConfigurationInstance()) {
            return;
        }

        $this->addProductConfigurationInstanceParameter($itemTransfer);
        $this->addProductConfigurationTemplateParameter($itemTransfer);
    }

    /**
     * @return string
     */
    public static function getName(): string
    {
        return 'ProductConfiguratorCartItemDisplayWidget';
    }

    /**
     * @return string
     */
    public static function getTemplate(): string
    {
        return '@ProductConfigurationWidget/views/product-configurator-cart-item-display-widget/product-configurator-cart-item-display-widget.twig';
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return void
     */
    protected function addIsVisibleParameter(ItemTransfer $itemTransfer): void
    {
        $this->addParameter(static::PARAMETER_IS_VISIBLE, $itemTransfer->getProductConfigurationInstance());
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return void
     */
    protected function addProductConfigurationInstanceParameter(ItemTransfer $itemTransfer): void
    {
        $this->addParameter(static::PARAMETER_PRODUCT_CONFIGURATION_INSTANCE, $itemTransfer->getProductConfigurationInstance());
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return void
     */
    protected function addProductConfigurationTemplateParameter(ItemTransfer $itemTransfer): void
    {
        $productConfigurationTemplateTransfer = $this->getFactory()
            ->createProductConfigurationTemplateResolver()
            ->resolveProductConfigurationTemplate($itemTransfer->getProductConfigurationInstance());

        $this->addParameter(static::PARAMETER_PRODUCT_CONFIGURATION_TEMPLATE, $productConfigurationTemplateTransfer);
    }
}
