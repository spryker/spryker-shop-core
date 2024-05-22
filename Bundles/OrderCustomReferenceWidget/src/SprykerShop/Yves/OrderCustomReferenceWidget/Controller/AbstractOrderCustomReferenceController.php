<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\OrderCustomReferenceWidget\Controller;

use Generated\Shared\Transfer\QuoteResponseTransfer;
use Spryker\Yves\Kernel\Controller\AbstractController;

abstract class AbstractOrderCustomReferenceController extends AbstractController
{
    /**
     * @var string
     */
    protected const GLOSSARY_KEY_ORDER_CUSTOM_REFERENCE_SAVED = 'order_custom_reference.reference_saved';

    /**
     * @param \Generated\Shared\Transfer\QuoteResponseTransfer $quoteResponseTransfer
     *
     * @return void
     */
    protected function handleQuoteResponseTransferErrors(QuoteResponseTransfer $quoteResponseTransfer): void
    {
        foreach ($quoteResponseTransfer->getErrors() as $quoteErrorTransfer) {
            $this->addErrorMessage($quoteErrorTransfer->getMessage());
        }
    }
}
