<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\OrderCustomReferenceWidget\Widget;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Yves\Kernel\Widget\AbstractWidget;
use SprykerShop\Yves\OrderCustomReferenceWidget\Form\OrderCustomReferenceForm;

/**
 * @method \SprykerShop\Yves\OrderCustomReferenceWidget\OrderCustomReferenceWidgetFactory getFactory()
 */
class OrderCustomReferenceWidget extends AbstractWidget
{
    protected const PARAMETER_QUOTE = 'quote';
    protected const PARAMETER_IS_EDITABLE = 'isEditable';

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
        $this->addOrderCustomReferenceFormParameter($quoteTransfer, $backUrl);
        $this->addIsEditableParameter($quoteTransfer);
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
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param string $backUrl
     *
     * @return void
     */
    protected function addOrderCustomReferenceFormParameter(QuoteTransfer $quoteTransfer, string $backUrl): void
    {
        $this->addParameter(
            static::FORM_ORDER_CUSTOM_REFERENCE,
            $this->getFactory()->getOrderCustomReferenceForm(
                [
                    OrderCustomReferenceForm::FIELD_ORDER_CUSTOM_REFERENCE => $quoteTransfer->getOrderCustomReference(),
                    OrderCustomReferenceForm::FIELD_BACK_URL => $backUrl,
                ]
            )->createView()
        );
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    protected function addIsEditableParameter(QuoteTransfer $quoteTransfer): void
    {
        $this->addParameter(static::PARAMETER_IS_EDITABLE, $this->isEditable($quoteTransfer));
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    protected function isEditable(QuoteTransfer $quoteTransfer): bool
    {
        if (!$quoteTransfer->getCustomer()) {
            return false;
        }

        if (!$quoteTransfer->getCustomer()->getCompanyUserTransfer()) {
            return false;
        }

        if (!$quoteTransfer->getCustomer()->getCompanyUserTransfer()->getIdCompanyUser()) {
            return false;
        }

        return true;
    }
}
