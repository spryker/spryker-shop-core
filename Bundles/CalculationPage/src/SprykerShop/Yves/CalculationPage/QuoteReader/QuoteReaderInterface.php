<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CalculationPage\QuoteReader;

use Generated\Shared\Transfer\QuoteTransfer;

interface QuoteReaderInterface
{
    /**
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function getRecalculatedQuote(): QuoteTransfer;
}
