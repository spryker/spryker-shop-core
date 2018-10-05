<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductBundleWidget\Plugin\MultiCartWidget;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Yves\Kernel\Widget\AbstractWidgetPlugin;
use SprykerShop\Yves\MultiCartWidget\Dependency\Plugin\ProductBundleWidget\ProductBundleItemsWidgetPluginInterface;
use SprykerShop\Yves\ProductBundleWidget\Widget\ProductBundleItemsMultiCartItemsListWidget;

/**
 * @deprecated Use \SprykerShop\Yves\ProductBundleWidget\Widget\ProductBundleItemsMultiCartItemsListWidget instead.
 *
 * @method \SprykerShop\Yves\ProductBundleWidget\ProductBundleWidgetFactory getFactory()
 */
class ProductBundleItemsWidgetPlugin extends AbstractWidgetPlugin implements ProductBundleItemsWidgetPluginInterface
{
    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param int|null $itemDisplayLimit
     *
     * @return void
     */
    public function initialize(QuoteTransfer $quoteTransfer, ?int $itemDisplayLimit = null): void
    {
        $widget = new ProductBundleItemsMultiCartItemsListWidget($quoteTransfer, $itemDisplayLimit);

        $this->parameters = $widget->getParameters();
    }

    /**
     * Specification:
     * - Returns the name of the widget as it's used in templates.
     *
     * @api
     *
     * @return string
     */
    public static function getName()
    {
        return static::NAME;
    }

    /**
     * Specification:
     * - Returns the the template file path to render the widget.
     *
     * @api
     *
     * @return string
     */
    public static function getTemplate()
    {
        return ProductBundleItemsMultiCartItemsListWidget::getTemplate();
    }
}
