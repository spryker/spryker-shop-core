<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\DiscountWidget\Plugin\CheckoutPage;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Yves\Kernel\Widget\AbstractWidgetPlugin;
use SprykerShop\Yves\CheckoutPage\Dependency\Plugin\DiscountWidget\CheckoutVoucherFormWidgetPluginInterface;
use SprykerShop\Yves\DiscountWidget\Widget\CheckoutVoucherFormWidget;

/**
 * @deprecated Use \SprykerShop\Yves\DiscountWidget\Widget\CheckoutVoucherFormWidget instead.
 */
class CheckoutVoucherFormWidgetPlugin extends AbstractWidgetPlugin implements CheckoutVoucherFormWidgetPluginInterface
{
    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    public function initialize(QuoteTransfer $quoteTransfer): void
    {
        $widget = new CheckoutVoucherFormWidget($quoteTransfer);

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
        return CheckoutVoucherFormWidget::getTemplate();
    }
}
