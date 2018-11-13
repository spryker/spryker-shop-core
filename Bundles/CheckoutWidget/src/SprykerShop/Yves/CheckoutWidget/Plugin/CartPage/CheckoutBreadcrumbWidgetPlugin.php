<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CheckoutWidget\Plugin\CartPage;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Yves\Kernel\Widget\AbstractWidgetPlugin;
use SprykerShop\Yves\CartPage\Dependency\Plugin\CheckoutWidget\CheckoutBreadcrumbWidgetPluginInterface;
use SprykerShop\Yves\CheckoutWidget\Widget\CheckoutBreadcrumbWidget;

/**
 * @deprecated Use \SprykerShop\Yves\CheckoutWidget\Widget\CheckoutBreadcrumbWidget instead.
 */
class CheckoutBreadcrumbWidgetPlugin extends AbstractWidgetPlugin implements CheckoutBreadcrumbWidgetPluginInterface
{
    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    public function initialize(QuoteTransfer $quoteTransfer): void
    {
        $widget = new CheckoutBreadcrumbWidget($quoteTransfer);

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
        return CheckoutBreadcrumbWidget::getTemplate();
    }
}
