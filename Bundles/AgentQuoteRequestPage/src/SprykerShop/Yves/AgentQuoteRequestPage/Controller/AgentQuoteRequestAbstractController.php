<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\AgentQuoteRequestPage\Controller;

use Generated\Shared\Transfer\QuoteRequestResponseTransfer;
use SprykerShop\Yves\ShopApplication\Controller\AbstractController;

/**
 * @method \SprykerShop\Yves\QuoteRequestPage\QuoteRequestPageFactory getFactory()
 */
class AgentQuoteRequestAbstractController extends AbstractController
{
    /**
     * @param \Generated\Shared\Transfer\QuoteRequestResponseTransfer $quoteRequestResponseTransfer
     *
     * @return void
     */
    protected function handleResponseErrors(QuoteRequestResponseTransfer $quoteRequestResponseTransfer): void
    {
        foreach ($quoteRequestResponseTransfer->getErrors() as $errorTransfer) {
            $this->addErrorMessage($errorTransfer);
        }
    }
}
