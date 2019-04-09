<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\QuoteRequestAgentPage\Form\DataProvider;

use Generated\Shared\Transfer\QuoteRequestTransfer;
use SprykerShop\Yves\QuoteRequestAgentPage\Form\QuoteRequestAgentForm;

class QuoteRequestAgentFormDataProvider
{
    /**
     * @see \Spryker\Shared\Calculation\CalculationPriceMode::PRICE_MODE_GROSS
     */
    protected const PRICE_MODE_GROSS = 'GROSS_MODE';

    /**
     * @param \Generated\Shared\Transfer\QuoteRequestTransfer $quoteRequestTransfer
     *
     * @return array
     */
    public function getOptions(QuoteRequestTransfer $quoteRequestTransfer): array
    {
        return [
            QuoteRequestAgentForm::OPTION_IS_DEFAULT_PRICE_MODE_GROSS => $this->isDefaultPriceModeGross($quoteRequestTransfer),
        ];
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteRequestTransfer $quoteRequestTransfer
     *
     * @return bool
     */
    protected function isDefaultPriceModeGross(QuoteRequestTransfer $quoteRequestTransfer): bool
    {
        return $quoteRequestTransfer->getLatestVersion()->getQuote()->getPriceMode() === static::PRICE_MODE_GROSS;
    }
}
