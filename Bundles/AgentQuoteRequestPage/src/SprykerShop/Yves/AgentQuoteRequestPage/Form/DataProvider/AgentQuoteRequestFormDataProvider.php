<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\AgentQuoteRequestPage\Form\DataProvider;

use Generated\Shared\Transfer\QuoteRequestTransfer;

class AgentQuoteRequestFormDataProvider
{
    /**
     * @param \Generated\Shared\Transfer\QuoteRequestTransfer $quoteRequestTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteRequestTransfer
     */
    public function getData(QuoteRequestTransfer $quoteRequestTransfer): QuoteRequestTransfer
    {
        return $quoteRequestTransfer;
    }
}
