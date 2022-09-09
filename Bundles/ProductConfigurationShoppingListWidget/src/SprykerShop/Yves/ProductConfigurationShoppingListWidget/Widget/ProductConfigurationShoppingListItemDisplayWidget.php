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
class ProductConfigurationShoppingListItemDisplayWidget extends AbstractWidget
{
    /**
     * @var string
     */
    protected const PARAMETER_IS_VISIBLE = 'isVisible';

    /**
     * @var string
     */
    protected const PARAMETER_PRODUCT_CONFIGURATION_INSTANCE = 'productConfigurationInstance';

    /**
     * @var string
     */
    protected const PARAMETER_PRODUCT_CONFIGURATION_TEMPLATE = 'productConfigurationTemplate';

    /**
     * @param \Generated\Shared\Transfer\ShoppingListItemTransfer $shoppingListItemTransfer
     */
    public function __construct(ShoppingListItemTransfer $shoppingListItemTransfer)
    {
        $this->addIsVisibleParameter($shoppingListItemTransfer);

        if (!$shoppingListItemTransfer->getProductConfigurationInstance()) {
            return;
        }

        $this->addProductConfigurationInstanceParameter($shoppingListItemTransfer);
        $this->addProductConfigurationTemplateParameter($shoppingListItemTransfer);
    }

    /**
     * @return string
     */
    public static function getName(): string
    {
        return 'ProductConfigurationShoppingListItemDisplayWidget';
    }

    /**
     * @return string
     */
    public static function getTemplate(): string
    {
        return '@ProductConfigurationShoppingListWidget/views/shopping-list-item-configuration/shopping-list-item-configuration.twig';
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
    protected function addProductConfigurationInstanceParameter(ShoppingListItemTransfer $shoppingListItemTransfer): void
    {
        $this->addParameter(static::PARAMETER_PRODUCT_CONFIGURATION_INSTANCE, $shoppingListItemTransfer->getProductConfigurationInstanceOrFail());
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListItemTransfer $shoppingListItemTransfer
     *
     * @return void
     */
    protected function addProductConfigurationTemplateParameter(ShoppingListItemTransfer $shoppingListItemTransfer): void
    {
        $productConfigurationTemplateTransfer = $this->getFactory()
            ->createProductConfigurationTemplateResolver()
            ->resolveProductConfigurationTemplate($shoppingListItemTransfer->getProductConfigurationInstanceOrFail());

        $this->addParameter(static::PARAMETER_PRODUCT_CONFIGURATION_TEMPLATE, $productConfigurationTemplateTransfer);
    }
}
