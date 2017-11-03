<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CheckoutPage\DataContainer;

use Spryker\Shared\Kernel\Transfer\AbstractTransfer;
use SprykerShop\Yves\CheckoutPage\Dependency\Client\CheckoutToQuoteInterface;
use Spryker\Yves\StepEngine\Dependency\DataContainer\DataContainerInterface;

class DataContainer implements DataContainerInterface
{
    /**
     * @var \Spryker\Client\Quote\QuoteClientInterface
     */
    protected $quoteClient;

    /**
     * @var \Generated\Shared\Transfer\QuoteTransfer
     */
    protected $quoteTransfer;

    /**
     * @param \SprykerShop\Yves\CheckoutPage\Dependency\Client\CheckoutToQuoteInterface $quoteClient
     */
    public function __construct(CheckoutToQuoteInterface $quoteClient)
    {
        $this->quoteClient = $quoteClient;
    }

    /**
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function get()
    {
        if (!$this->quoteTransfer) {
            $this->quoteTransfer = $this->quoteClient->getQuote();
        }

        return $this->quoteTransfer;
    }

    /**
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer|\Generated\Shared\Transfer\QuoteTransfer $dataTransfer
     *
     * @return void
     */
    public function set(AbstractTransfer $dataTransfer)
    {
        $this->quoteClient->setQuote($dataTransfer);
    }
}
