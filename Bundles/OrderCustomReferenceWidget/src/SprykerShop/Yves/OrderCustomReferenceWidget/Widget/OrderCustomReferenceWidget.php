<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\OrderCustomReferenceWidget\Widget;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Yves\Kernel\Widget\AbstractWidget;

/**
 * @method \SprykerShop\Yves\OrderCustomReferenceWidget\OrderCustomReferenceWidgetFactory getFactory()
 */
class OrderCustomReferenceWidget extends AbstractWidget
{
    protected const PARAMETER_QUOTE = 'quote';
    protected const PARAMETER_ORDER_CUSTOM_REFERENCE = 'orderCustomReference';
    protected const PARAMETER_BACK_URL = 'backUrl';

    protected const FORM_ORDER_CUSTOM_REFERENCE = 'orderCustomReferenceForm';

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param string $backUrl
     */
    public function __construct(
        QuoteTransfer $quoteTransfer,
        string $backUrl
    ) {
        $this->addQuoteParameter($quoteTransfer);
        $this->addOrderCustomReferenceFormParameter($quoteTransfer->getOrderCustomReference(), $backUrl);
    }

    /**
     * @return string
     */
    public static function getName(): string
    {
        return 'OrderCustomReferenceWidget';
    }

    /**
     * @return string
     */
    public static function getTemplate(): string
    {
        return '@OrderCustomReferenceWidget/views/order-custom-reference/order-custom-reference.twig';
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    protected function addQuoteParameter(QuoteTransfer $quoteTransfer): void
    {
        $this->addParameter(static::PARAMETER_QUOTE, $quoteTransfer);
    }

    /**
     * @param string|null $orderCustomReference
     * @param string $backUrl
     *
     * @return void
     */
    protected function addOrderCustomReferenceFormParameter(?string $orderCustomReference, string $backUrl): void
    {
        $this->addParameter(
            static::FORM_ORDER_CUSTOM_REFERENCE,
            $this->getFactory()->getOrderCustomReferenceForm(
                [
                    static::PARAMETER_ORDER_CUSTOM_REFERENCE => $orderCustomReference,
                    static::PARAMETER_BACK_URL => $backUrl,
                ]
            )->createView()
        );
    }
}
