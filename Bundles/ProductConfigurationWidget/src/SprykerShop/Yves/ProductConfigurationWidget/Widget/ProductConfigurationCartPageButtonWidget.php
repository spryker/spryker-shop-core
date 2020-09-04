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
class ProductConfigurationCartPageButtonWidget extends AbstractWidget
{
    protected const PARAMETER_IS_VISIBLE = 'isVisible';
    protected const PARAMETER_FORM = 'form';
    protected const PARAMETER_PRODUCT_CONFIGURATION_ROUTE_NAME = 'productConfigurationRouteName';
    protected const PARAMETER_SOURCE_TYPE = 'sourceType';
    protected const PARAMETER_ITEM_GROUP_KEY = 'itemGroupKey';
    protected const PARAMETER_QUANTITY = 'quantity';

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     */
    public function __construct(ItemTransfer $itemTransfer)
    {
        $this->addIsVisibleParameter($itemTransfer);

        if (!$itemTransfer->getProductConfigurationInstance()) {
            return;
        }

        $this->addFormParameter($itemTransfer);
        $this->addProductConfigurationRouteNameParameter();
    }

    /**
     * @return string
     */
    public static function getName(): string
    {
        return 'ProductConfigurationCartPageButtonWidget';
    }

    /**
     * @return string
     */
    public static function getTemplate(): string
    {
        return '@ProductConfigurationWidget/views/cart-item-configuration-button/cart-item-configuration-button.twig';
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return void
     */
    protected function addIsVisibleParameter(ItemTransfer $itemTransfer): void
    {
        $this->addParameter(static::PARAMETER_IS_VISIBLE, (bool)$itemTransfer->getProductConfigurationInstance());
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return void
     */
    protected function addFormParameter(ItemTransfer $itemTransfer): void
    {
        $this->addParameter(static::PARAMETER_FORM, $this->getFactory()->getProductConfigurationButtonForm()
            ->setData(
                [
                    static::PARAMETER_SOURCE_TYPE => $this->getConfig()->getCartSourceType(),
                    static::PARAMETER_ITEM_GROUP_KEY => $itemTransfer->getGroupKey(),
                    static::PARAMETER_QUANTITY => $itemTransfer->getQuantity(),
                ]
            )->createView());
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
}
