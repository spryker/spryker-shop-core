<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ShoppingListWidget\Plugin\ProductDetailPage;

use Spryker\Yves\Kernel\PermissionAwareTrait;
use Spryker\Yves\Kernel\Widget\AbstractWidgetPlugin;
use SprykerShop\Yves\ProductDetailPage\Dependency\Plugin\ShoppingListWidget\ShoppingListWidgetPluginInterface;
use SprykerShop\Yves\ShoppingListWidget\Widget\AddToShoppingListWidget;

/**
 * @deprecated Use \SprykerShop\Yves\ShoppingListWidget\Widget\AddToShoppingListWidget instead.
 *
 * @method \SprykerShop\Yves\ShoppingListWidget\ShoppingListWidgetFactory getFactory()
 */
class ShoppingListWidgetPlugin extends AbstractWidgetPlugin implements ShoppingListWidgetPluginInterface
{
    use PermissionAwareTrait;

    /**
     * @param string $sku
     * @param bool $isDisabled
     *
     * @return void
     */
    public function initialize(string $sku, bool $isDisabled): void
    {
        $widget = new AddToShoppingListWidget($sku, $isDisabled);

        $this->parameters = $widget->getParameters();
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return string
     */
    public static function getName(): string
    {
        return static::NAME;
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return string
     */
    public static function getTemplate(): string
    {
        return AddToShoppingListWidget::getTemplate();
    }
}
