<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductConfigurationShoppingListWidget\Widget;

use Generated\Shared\Transfer\ShoppingListItemTransfer;
use Spryker\Yves\Kernel\Widget\AbstractWidget;

/**
 * @method \SprykerShop\Yves\ProductConfigurationShoppingListWidget\ProductConfigurationShoppingListWidgetFactory getFactory()
 * @method \SprykerShop\Yves\ProductConfigurationShoppingListWidget\ProductConfigurationShoppingListWidgetConfig getConfig()
 */
class ProductConfigurationShoppingListPageButtonWidget extends AbstractWidget
{
    /**
     * @var string
     */
    protected const PARAMETER_IS_VISIBLE = 'isVisible';

    /**
     * @var string
     */
    protected const PARAMETER_FORM = 'form';

    /**
     * @var string
     */
    protected const PARAMETER_PRODUCT_CONFIGURATOR_ROUTE_NAME = 'productConfiguratorRouteName';

    /**
     * @param \Generated\Shared\Transfer\ShoppingListItemTransfer $shoppingListItemTransfer
     */
    public function __construct(ShoppingListItemTransfer $shoppingListItemTransfer)
    {
        $this->addIsVisibleParameter($shoppingListItemTransfer);

        if (!$shoppingListItemTransfer->getProductConfigurationInstance()) {
            return;
        }

        $this->addFormParameter($shoppingListItemTransfer);
        $this->addProductConfigurationRouteNameParameter();
    }

    /**
     * @return string
     */
    public static function getName(): string
    {
        return 'ProductConfigurationShoppingListPageButtonWidget';
    }

    /**
     * @return string
     */
    public static function getTemplate(): string
    {
        return '@ProductConfigurationShoppingListWidget/views/shopping-list-item-configuration-button/shopping-list-item-configuration-button.twig';
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListItemTransfer $shoppingListItemTransfer
     *
     * @return void
     */
    protected function addIsVisibleParameter(ShoppingListItemTransfer $shoppingListItemTransfer): void
    {
        $this->addParameter(static::PARAMETER_IS_VISIBLE, $shoppingListItemTransfer->getProductConfigurationInstance() !== null);
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListItemTransfer $shoppingListItemTransfer
     *
     * @return void
     */
    protected function addFormParameter(ShoppingListItemTransfer $shoppingListItemTransfer): void
    {
        $productConfiguratorButtonFormCartPageDataProvider = $this->getFactory()
            ->createProductConfiguratorButtonFormDataProvider();

        $productConfigurationButtonForm = $this->getFactory()
            ->getProductConfigurationButtonForm()
            ->setData($productConfiguratorButtonFormCartPageDataProvider->getData($shoppingListItemTransfer))
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
            $this->getConfig()->getProductConfiguratorGatewayRequestRoute(),
        );
    }
}
