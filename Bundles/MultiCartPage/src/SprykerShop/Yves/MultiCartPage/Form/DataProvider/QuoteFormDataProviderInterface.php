<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\MultiCartPage\Form\DataProvider;

use Generated\Shared\Transfer\QuoteTransfer;

interface QuoteFormDataProviderInterface
{
    /**
     * @param int|null $idQuote
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer|null
     */
    public function getData(?int $idQuote = null): ?QuoteTransfer;
}
