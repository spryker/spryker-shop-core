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
class UpdateOrderCheckoutSubmitButtonTextWidget extends AbstractWidget
{
    /**
     * @var string
     */
    protected const PARAMETER_SUBMIT_BUTTON_TEXT = 'submitButtonText';

    /**
     * @var string
     */
    protected const UPDATE_ORDER_CHECKOUT_SUBMIT_BUTTON_TEXT = 'sales_order_amendment_widget.summary_step.update.order';

    /**
     * @param string $defaultSubmitButtonText
     * @param \Generated\Shared\Transfer\QuoteTransfer|null $quoteTransfer
     */
    public function __construct(string $defaultSubmitButtonText, ?QuoteTransfer $quoteTransfer = null)
    {
        $this->addSubmitButtonTextParameter($defaultSubmitButtonText, $quoteTransfer);
    }

    /**
     * @return string
     */
    public static function getName(): string
    {
        return 'UpdateOrderCheckoutSubmitButtonTextWidget';
    }

    /**
     * @return string
     */
    public static function getTemplate(): string
    {
        return '@SalesOrderAmendmentWidget/views/update-order-checkout-submit-button-text/update-order-checkout-submit-button-text.twig';
    }

    /**
     * @param string $defaultSubmitButtonText
     * @param \Generated\Shared\Transfer\QuoteTransfer|null $quoteTransfer
     *
     * @return void
     */
    protected function addSubmitButtonTextParameter(string $defaultSubmitButtonText, ?QuoteTransfer $quoteTransfer = null): void
    {
        $this->addParameter(
            static::PARAMETER_SUBMIT_BUTTON_TEXT,
            $quoteTransfer && $quoteTransfer->getAmendmentOrderReference() ? static::UPDATE_ORDER_CHECKOUT_SUBMIT_BUTTON_TEXT : $defaultSubmitButtonText,
        );
    }
}
