<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\AgentQuoteRequestWidget\Dependency\Client;

use Generated\Shared\Transfer\CompanyUserAutocompleteResponseTransfer;
use Generated\Shared\Transfer\CompanyUserQueryTransfer;
use Generated\Shared\Transfer\QuoteRequestOverviewCollectionTransfer;
use Generated\Shared\Transfer\QuoteRequestOverviewFilterTransfer;
use Generated\Shared\Transfer\QuoteRequestResponseTransfer;
use Generated\Shared\Transfer\QuoteRequestTransfer;

interface AgentQuoteRequestWidgetToAgentQuoteRequestClientInterface
{
    /**
     * @param \Generated\Shared\Transfer\QuoteRequestTransfer $quoteRequestTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteRequestResponseTransfer
     */
    public function updateQuoteRequest(QuoteRequestTransfer $quoteRequestTransfer): QuoteRequestResponseTransfer;

    /**
     * @param \Generated\Shared\Transfer\QuoteRequestOverviewFilterTransfer $quoteRequestOverviewFilterTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteRequestOverviewCollectionTransfer
     */
    public function getQuoteRequestOverviewCollection(
        QuoteRequestOverviewFilterTransfer $quoteRequestOverviewFilterTransfer
    ): QuoteRequestOverviewCollectionTransfer;

    /**
     * @param \Generated\Shared\Transfer\CompanyUserQueryTransfer $customerQueryTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUserAutocompleteResponseTransfer
     */
    public function getCompanyUsersByQuery(CompanyUserQueryTransfer $customerQueryTransfer): CompanyUserAutocompleteResponseTransfer;

    /**
     * @param string $quoteRequestReference
     *
     * @return \Generated\Shared\Transfer\QuoteRequestTransfer|null
     */
    public function findQuoteRequestByReference(string $quoteRequestReference): ?QuoteRequestTransfer;
}
