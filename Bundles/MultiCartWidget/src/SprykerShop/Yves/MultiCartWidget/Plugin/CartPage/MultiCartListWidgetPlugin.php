<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\MultiCartWidget\Plugin\CartPage;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Yves\Kernel\Widget\AbstractWidgetPlugin;
use SprykerShop\Yves\CartPage\Dependency\Plugin\MultiCartWidget\MultiCartListWidgetPluginInterface;
use SprykerShop\Yves\MultiCartWidget\Widget\MultiCartListWidget;

/**
 * @deprecated Use \SprykerShop\Yves\MultiCartWidget\Widget\MultiCartListWidget instead.
 *
 * @method \SprykerShop\Yves\MultiCartWidget\MultiCartWidgetFactory getFactory()
 */
class MultiCartListWidgetPlugin extends AbstractWidgetPlugin implements MultiCartListWidgetPluginInterface
{
    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    public function initialize(QuoteTransfer $quoteTransfer): void
    {
        $widget = new MultiCartListWidget($quoteTransfer);

        $this->parameters = $widget->getParameters();

        $this->addWidgets($this->getFactory()->getViewExtendWidgetPlugins());
    }

    /**
     * {@inheritDoc}
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
     * {@inheritDoc}
     *
     * @api
     *
     * @return string
     */
    public static function getTemplate()
    {
        return MultiCartListWidget::getTemplate();
    }
}
