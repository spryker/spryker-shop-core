<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductBundleWidget\Plugin\MultiCartWidget;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Yves\Kernel\Widget\AbstractWidgetPlugin;
use SprykerShop\Yves\MultiCartWidget\Dependency\Plugin\ProductBundleWidget\ProductBundleCartItemsWidgetPluginInterface;
use SprykerShop\Yves\ProductBundleWidget\Widget\ProductBundleMultiCartItemsListWidget;

/**
 * @deprecated Use \SprykerShop\Yves\ProductBundleWidget\Widget\ProductBundleMultiCartItemsListWidget instead.
 *
 * @method \SprykerShop\Yves\ProductBundleWidget\ProductBundleWidgetFactory getFactory()
 */
class ProductBundleCartItemsWidgetPlugin extends AbstractWidgetPlugin implements ProductBundleCartItemsWidgetPluginInterface
{
    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param int|null $itemDisplayLimit
     *
     * @return void
     */
    public function initialize(QuoteTransfer $quoteTransfer, ?int $itemDisplayLimit = null): void
    {
        $widget = new ProductBundleMultiCartItemsListWidget($quoteTransfer, $itemDisplayLimit);

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
    public static function getName(): string
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
    public static function getTemplate(): string
    {
        return ProductBundleMultiCartItemsListWidget::getTemplate();
    }
}
