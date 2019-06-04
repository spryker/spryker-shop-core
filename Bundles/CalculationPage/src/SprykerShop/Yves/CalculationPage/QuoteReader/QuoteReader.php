<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CalculationPage\QuoteReader;

use Generated\Shared\Transfer\QuoteTransfer;
use SprykerShop\Yves\CalculationPage\CalculationPageConfig;
use SprykerShop\Yves\CalculationPage\Dependency\Client\CalculationPageToCalculationClientInterface;
use SprykerShop\Yves\CalculationPage\Dependency\Client\CalculationPageToQuoteClientInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class QuoteReader implements QuoteReaderInterface
{
    /**
     * @var \SprykerShop\Yves\CalculationPage\Dependency\Client\CalculationPageToQuoteClientInterface
     */
    protected $quoteClient;

    /**
     * @var \SprykerShop\Yves\CalculationPage\Dependency\Client\CalculationPageToCalculationClientInterface
     */
    protected $calculationClient;

    /**
     * @var \SprykerShop\Yves\CalculationPage\CalculationPageConfig
     */
    protected $calculationPageConfig;

    /**
     * @param \SprykerShop\Yves\CalculationPage\Dependency\Client\CalculationPageToQuoteClientInterface $quoteClient
     * @param \SprykerShop\Yves\CalculationPage\Dependency\Client\CalculationPageToCalculationClientInterface $calculationClient
     * @param \SprykerShop\Yves\CalculationPage\CalculationPageConfig $calculationPageConfig
     */
    public function __construct(
        CalculationPageToQuoteClientInterface $quoteClient,
        CalculationPageToCalculationClientInterface $calculationClient,
        CalculationPageConfig $calculationPageConfig
    ) {
        $this->quoteClient = $quoteClient;
        $this->calculationClient = $calculationClient;
        $this->calculationPageConfig = $calculationPageConfig;
    }

    /**
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function getRecalculatedQuote(): QuoteTransfer
    {
        if (!$this->calculationPageConfig->isCartDebugEnabled()) {
            throw new NotFoundHttpException();
        }

        $quoteTransfer = $this->quoteClient->getQuote();
        $quoteTransfer = $this->calculationClient->recalculate($quoteTransfer);

        return $quoteTransfer;
    }
}
