<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\AgentQuoteRequestPage\Form\Handler;

use Generated\Shared\Transfer\QuoteRequestResponseTransfer;

interface AgentQuoteRequestCreateHandlerInterface
{
    /**
     * @param int|null $idCompanyUser
     *
     * @return \Generated\Shared\Transfer\QuoteRequestResponseTransfer
     */
    public function createQuoteRequest(?int $idCompanyUser): QuoteRequestResponseTransfer;
}
