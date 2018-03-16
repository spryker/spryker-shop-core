<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\MultiCartPage\Model;

use ArrayObject;
use Generated\Shared\Transfer\QuoteActivatorRequestTransfer;
use Generated\Shared\Transfer\QuoteResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use SprykerShop\Yves\MultiCartPage\Dependency\Client\MultiCartPageToMultiCartClientInterface;
use SprykerShop\Yves\MultiCartPage\Dependency\Client\MultiCartPageToPersistentCartClientInterface;
use SprykerShop\Yves\MultiCartPage\Dependency\Client\MultiCartPageToQuoteClientInterface;

class CartOperations implements CartOperationsInterface
{
    /**
     * @var \SprykerShop\Yves\MultiCartPage\Dependency\Client\MultiCartPageToPersistentCartClientInterface
     */
    protected $persistentCartClient;

    /**
     * @var \SprykerShop\Yves\MultiCartPage\Dependency\Client\MultiCartPageToMultiCartClientInterface
     */
    protected $multiCartClient;

    /**
     * @var \SprykerShop\Yves\MultiCartPage\Dependency\Client\MultiCartPageToQuoteClientInterface
     */
    protected $quoteClient;

    /**
     * @param \SprykerShop\Yves\MultiCartPage\Dependency\Client\MultiCartPageToPersistentCartClientInterface $persistentCartClient
     * @param \SprykerShop\Yves\MultiCartPage\Dependency\Client\MultiCartPageToMultiCartClientInterface $multiCartClient
     * @param \SprykerShop\Yves\MultiCartPage\Dependency\Client\MultiCartPageToQuoteClientInterface $quoteClient
     */
    public function __construct(
        MultiCartPageToPersistentCartClientInterface $persistentCartClient,
        MultiCartPageToMultiCartClientInterface $multiCartClient,
        MultiCartPageToQuoteClientInterface $quoteClient
    ) {
        $this->persistentCartClient = $persistentCartClient;
        $this->multiCartClient = $multiCartClient;
        $this->quoteClient = $quoteClient;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function createQuote(QuoteTransfer $quoteTransfer): QuoteResponseTransfer
    {
        $quoteTransfer->setIdQuote(null);
        $quoteResponseTransfer = $this->persistentCartClient->persistQuote($quoteTransfer);

        return $quoteResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function updateQuote(QuoteTransfer $quoteTransfer): QuoteResponseTransfer
    {
        $quoteTransfer->requireIdQuote();
        $quoteResponseTransfer = $this->persistentCartClient->persistQuote($quoteTransfer);

        return $quoteResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function deleteQuote(QuoteTransfer $quoteTransfer): QuoteResponseTransfer
    {
        $quoteResponseTransfer = $this->persistentCartClient->deleteQuote($quoteTransfer);

        return $quoteResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function duplicateQuote(QuoteTransfer $quoteTransfer): QuoteResponseTransfer
    {
        $quoteTransfer = clone $quoteTransfer;
        $quoteTransfer->setName(
            $quoteTransfer->getName() . $this->multiCartClient->getDuplicatedQuoteNameSuffix()
        );
        $quoteTransfer->setIdQuote(null);
        $quoteTransfer->setIsActive(true);

        return $this->persistentCartClient->persistQuote($quoteTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function setActiveQuote(QuoteTransfer $quoteTransfer): QuoteResponseTransfer
    {
        $quoteActivatorRequestTransfer = new QuoteActivatorRequestTransfer();
        $quoteActivatorRequestTransfer->setCustomer($quoteTransfer->getCustomer());
        $quoteActivatorRequestTransfer->setIdQuote($quoteTransfer->getIdQuote());
        $quoteResponseTransfer = $this->multiCartClient->setActiveQuote($quoteActivatorRequestTransfer);
        if ($quoteResponseTransfer->getIsSuccessful()) {
            $this->quoteClient->setQuote($quoteResponseTransfer->getQuoteTransfer());
        }

        return $quoteResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function clearQuote(QuoteTransfer $quoteTransfer): QuoteResponseTransfer
    {
        $quoteTransfer->setItems(new ArrayObject());
        $quoteResponseTransfer = $this->persistentCartClient->persistQuote($quoteTransfer);

        return $quoteResponseTransfer;
    }
}
