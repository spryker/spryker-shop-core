<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CartToShoppingListWidget\Plugin\CartPage;

use Spryker\Yves\Kernel\Widget\AbstractWidgetPlugin;
use SprykerShop\Yves\CartPage\Dependency\Plugin\CartToShoppingListWidget\CartToShoppingListWidgetPluginInterface;
use SprykerShop\Yves\CartToShoppingListWidget\Widget\CreateShoppingListFromCartWidget;

/**
 * @deprecated Use \SprykerShop\Yves\CartToShoppingListWidget\Widget\CreateShoppingListFromCartWidget instead.
 *
 * @method \SprykerShop\Yves\CartToShoppingListWidget\CartToShoppingListWidgetFactory getFactory()
 */
class CartToShoppingListWidgetPlugin extends AbstractWidgetPlugin implements CartToShoppingListWidgetPluginInterface
{
    /**
     * @param int $idQuote
     *
     * @return void
     */
    public function initialize(int $idQuote): void
    {
        $widget = new CreateShoppingListFromCartWidget($idQuote);

        $this->parameters = $widget->getParameters();
    }

    /**
     * @return string
     */
    public static function getName(): string
    {
        return static::NAME;
    }

    /**
     * @return string
     */
    public static function getTemplate(): string
    {
        return CreateShoppingListFromCartWidget::getTemplate();
    }
}
