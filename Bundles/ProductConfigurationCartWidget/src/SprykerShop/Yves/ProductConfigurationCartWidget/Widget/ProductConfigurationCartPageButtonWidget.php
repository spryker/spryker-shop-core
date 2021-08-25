<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductConfigurationCartWidget\Widget;

use Generated\Shared\Transfer\ItemTransfer;
use Spryker\Yves\Kernel\Widget\AbstractWidget;

/**
 * @method \SprykerShop\Yves\ProductConfigurationCartWidget\ProductConfigurationCartWidgetFactory getFactory()
 * @method \SprykerShop\Yves\ProductConfigurationCartWidget\ProductConfigurationCartWidgetConfig getConfig()
 */
class ProductConfigurationCartPageButtonWidget extends AbstractWidget
{
    protected const PARAMETER_IS_VISIBLE = 'isVisible';
    protected const PARAMETER_FORM = 'form';
    protected const PARAMETER_PRODUCT_CONFIGURATOR_ROUTE_NAME = 'productConfiguratorRouteName';

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
        return '@ProductConfigurationCartWidget/views/cart-item-configuration-button/cart-item-configuration-button.twig';
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return void
     */
    protected function addIsVisibleParameter(ItemTransfer $itemTransfer): void
    {
        $this->addParameter(static::PARAMETER_IS_VISIBLE, $itemTransfer->getProductConfigurationInstance() !== null);
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return void
     */
    protected function addFormParameter(ItemTransfer $itemTransfer): void
    {
        $productConfiguratorButtonFormCartPageDataProvider = $this->getFactory()
            ->createProductConfigurationButtonFormDataProvider();

        $productConfigurationButtonForm = $this->getFactory()
            ->getProductConfigurationButtonForm()
            ->setData($productConfiguratorButtonFormCartPageDataProvider->getData($itemTransfer))
            ->createView();

        $this->addParameter(static::PARAMETER_FORM, $productConfigurationButtonForm);
    }

    /**
     * @return void
     */
    protected function addProductConfigurationRouteNameParameter(): void
    {
        $this->addParameter(
            static::PARAMETER_PRODUCT_CONFIGURATOR_ROUTE_NAME,
            $this->getConfig()->getProductConfiguratorGatewayRequestRoute()
        );
    }
}
