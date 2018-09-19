<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductRelationWidget\Plugin\CartPage;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Yves\Kernel\Widget\AbstractWidgetPlugin;
use SprykerShop\Yves\CartPage\Dependency\Plugin\ProductRelationWidget\UpSellingProductsWidgetPluginInterface;
use SprykerShop\Yves\ProductRelationWidget\Widget\UpSellingProductsWidget;

/**
 * @deprecated Use \SprykerShop\Yves\ProductRelationWidget\Widget\UpSellingProductsWidget instead.
 *
 * @method \SprykerShop\Yves\ProductRelationWidget\ProductRelationWidgetFactory getFactory()
 */
class UpSellingProductsWidgetPlugin extends AbstractWidgetPlugin implements UpSellingProductsWidgetPluginInterface
{
    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    public function initialize(QuoteTransfer $quoteTransfer): void
    {
        $widget = new UpSellingProductsWidget($quoteTransfer);

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
        return UpSellingProductsWidget::getTemplate();
    }
}
