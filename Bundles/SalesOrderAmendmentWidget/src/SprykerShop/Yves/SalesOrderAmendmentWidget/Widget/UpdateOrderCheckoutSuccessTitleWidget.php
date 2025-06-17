<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\SalesOrderAmendmentWidget\Widget;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Yves\Kernel\Widget\AbstractWidget;

/**
 * @method \SprykerShop\Yves\SalesOrderAmendmentWidget\SalesOrderAmendmentWidgetFactory getFactory()
 * @method \SprykerShop\Yves\SalesOrderAmendmentWidget\SalesOrderAmendmentWidgetConfig getConfig()
 */
class UpdateOrderCheckoutSuccessTitleWidget extends AbstractWidget
{
    /**
     * @var string
     */
    protected const PARAMETER_SUCCESS_TITLE = 'successTitle';

    /**
     * @var string
     */
    protected const UPDATE_ORDER_CHECKOUT_SUCCESS_TITLE = 'sales_order_amendment_widget.success_step.update.success.title';

    /**
     * @param string $defaultSuccessTitle
     * @param \Generated\Shared\Transfer\QuoteTransfer|null $quoteTransfer
     */
    public function __construct(string $defaultSuccessTitle, ?QuoteTransfer $quoteTransfer = null)
    {
        $this->addSuccessTitleParameter($defaultSuccessTitle, $quoteTransfer);
    }

    /**
     * @return string
     */
    public static function getName(): string
    {
        return 'UpdateOrderCheckoutSuccessTitleWidget';
    }

    /**
     * @return string
     */
    public static function getTemplate(): string
    {
        return '@SalesOrderAmendmentWidget/views/update-order-checkout-success-title/update-order-checkout-success-title.twig';
    }

    /**
     * @param string $defaultSuccessTitle
     * @param \Generated\Shared\Transfer\QuoteTransfer|null $quoteTransfer
     *
     * @return void
     */
    protected function addSuccessTitleParameter(string $defaultSuccessTitle, ?QuoteTransfer $quoteTransfer = null): void
    {
        $this->addParameter(
            static::PARAMETER_SUCCESS_TITLE,
            $quoteTransfer && $quoteTransfer->getAmendmentOrderReference() ? static::UPDATE_ORDER_CHECKOUT_SUCCESS_TITLE : $defaultSuccessTitle,
        );
    }
}
