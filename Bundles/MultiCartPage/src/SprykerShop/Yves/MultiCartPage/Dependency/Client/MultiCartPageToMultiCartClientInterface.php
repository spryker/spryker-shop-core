<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\MultiCartPage\Dependency\Client;

use Generated\Shared\Transfer\QuoteResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;

interface MultiCartPageToMultiCartClientInterface
{
    /**
     * @return null|\Generated\Shared\Transfer\QuoteTransfer
     */
    public function findActiveCart(): ?QuoteTransfer;

    /**
     * @return \Generated\Shared\Transfer\QuoteCollectionTransfer
     */
    public function getQuoteCollection();

    /**
     * @param string $quoteName
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer|null
     */
    public function findQuoteByName($quoteName): ?QuoteTransfer;

    /**
     * @return bool
     */
    public function isMultiCartAllowed();

    /**
     * @return string
     */
    public function getDuplicatedQuoteNameSuffix();
}
