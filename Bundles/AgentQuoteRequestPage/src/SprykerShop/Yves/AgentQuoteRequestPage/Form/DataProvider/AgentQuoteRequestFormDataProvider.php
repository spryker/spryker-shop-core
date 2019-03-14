<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\AgentQuoteRequestPage\Form\DataProvider;

use Generated\Shared\Transfer\QuoteRequestTransfer;
use SprykerShop\Yves\AgentQuoteRequestPage\Dependency\Client\AgentQuoteRequestPageToQuoteRequestClientInterface;
use SprykerShop\Yves\AgentQuoteRequestPage\Form\AgentQuoteRequestForm;

class AgentQuoteRequestFormDataProvider
{
    /**
     * @see \Spryker\Shared\Calculation\CalculationPriceMode::PRICE_MODE_GROSS
     */
    protected const PRICE_MODE_GROSS = 'GROSS_MODE';

    /**
     * @var \SprykerShop\Yves\AgentQuoteRequestPage\Dependency\Client\AgentQuoteRequestPageToQuoteRequestClientInterface
     */
    protected $quoteRequestClient;

    /**
     * @param \SprykerShop\Yves\AgentQuoteRequestPage\Dependency\Client\AgentQuoteRequestPageToQuoteRequestClientInterface $quoteRequestClient
     */
    public function __construct(AgentQuoteRequestPageToQuoteRequestClientInterface $quoteRequestClient)
    {
        $this->quoteRequestClient = $quoteRequestClient;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteRequestTransfer $quoteRequestTransfer
     *
     * @return array
     */
    public function getOptions(QuoteRequestTransfer $quoteRequestTransfer): array
    {
        return [
            AgentQuoteRequestForm::OPTION_IS_DEFAULT_PRICE_MODE_GROSS => $this->isDefaultPriceModeGross($quoteRequestTransfer),
        ];
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteRequestTransfer $quoteRequestTransfer
     *
     * @return bool
     */
    protected function isDefaultPriceModeGross(QuoteRequestTransfer $quoteRequestTransfer): bool
    {
        return $quoteRequestTransfer->getQuoteInProgress()->getPriceMode() === static::PRICE_MODE_GROSS;
    }
}
