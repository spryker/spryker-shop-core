<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\MultiCartPage\Model;

use ArrayObject;
use Generated\Shared\Transfer\QuoteActivationRequestTransfer;
use Generated\Shared\Transfer\QuoteResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\QuoteUpdateRequestAttributesTransfer;
use Generated\Shared\Transfer\QuoteUpdateRequestTransfer;
use SprykerShop\Yves\MultiCartPage\Dependency\Client\MultiCartPageToCustomerClientInterface;
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
     * @var \SprykerShop\Yves\MultiCartPage\Dependency\Client\MultiCartPageToCustomerClientInterface
     */
    protected $customerClient;

    /**
     * @param \SprykerShop\Yves\MultiCartPage\Dependency\Client\MultiCartPageToPersistentCartClientInterface $persistentCartClient
     * @param \SprykerShop\Yves\MultiCartPage\Dependency\Client\MultiCartPageToMultiCartClientInterface $multiCartClient
     * @param \SprykerShop\Yves\MultiCartPage\Dependency\Client\MultiCartPageToQuoteClientInterface $quoteClient
     * @param \SprykerShop\Yves\MultiCartPage\Dependency\Client\MultiCartPageToCustomerClientInterface $customerClient
     */
    public function __construct(
        MultiCartPageToPersistentCartClientInterface $persistentCartClient,
        MultiCartPageToMultiCartClientInterface $multiCartClient,
        MultiCartPageToQuoteClientInterface $quoteClient,
        MultiCartPageToCustomerClientInterface $customerClient
    ) {
        $this->persistentCartClient = $persistentCartClient;
        $this->multiCartClient = $multiCartClient;
        $this->quoteClient = $quoteClient;
        $this->customerClient = $customerClient;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function createQuote(QuoteTransfer $quoteTransfer): QuoteResponseTransfer
    {
        $quoteTransfer->setIdQuote(null);
        $quoteTransfer->setCustomer(
            $this->customerClient->getCustomer()
        );
        $quoteResponseTransfer = $this->persistentCartClient->createQuote($quoteTransfer);

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
        $quoteTransfer->setCustomer(
            $this->customerClient->getCustomer()
        );
        $quoteUpdateRequestTransfer = $this->createQuoteUpdateRequest($quoteTransfer);
        $quoteUpdateRequestTransfer->getQuoteUpdateRequestAttributes()->fromArray($quoteTransfer->modifiedToArray(), true);
        $quoteResponseTransfer = $this->persistentCartClient->updateQuote($quoteUpdateRequestTransfer);

        return $quoteResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function deleteQuote(QuoteTransfer $quoteTransfer): QuoteResponseTransfer
    {
        $quoteTransfer->setCustomer(
            $this->customerClient->getCustomer()
        );
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
            $quoteTransfer->getName() . $this->multiCartClient->getDuplicatedQuoteNameSuffix() . ' ' . date('Y-m-d H:i:s')
        );
        $quoteTransfer->setIdQuote(null);
        $quoteTransfer->setIsDefault(true);
        $quoteTransfer->setCustomer(
            $this->customerClient->getCustomer()
        );

        return $this->persistentCartClient->createQuote($quoteTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function setDefaultQuote(QuoteTransfer $quoteTransfer): QuoteResponseTransfer
    {
        $quoteTransfer->setCustomer(
            $this->customerClient->getCustomer()
        );
        $quoteActivationRequestTransfer = new QuoteActivationRequestTransfer();
        $quoteActivationRequestTransfer->setCustomer($quoteTransfer->getCustomer());
        $quoteActivationRequestTransfer->setIdQuote($quoteTransfer->getIdQuote());
        $quoteResponseTransfer = $this->multiCartClient->setDefaultQuote($quoteActivationRequestTransfer);
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
        $quoteTransfer->setCustomer(
            $this->customerClient->getCustomer()
        );
        $quoteUpdateRequestTransfer = $this->createQuoteUpdateRequest($quoteTransfer);
        $quoteUpdateRequestTransfer->getQuoteUpdateRequestAttributes()->setItems(new ArrayObject());

        return $this->persistentCartClient->updateQuote($quoteUpdateRequestTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteUpdateRequestTransfer
     */
    protected function createQuoteUpdateRequest(QuoteTransfer $quoteTransfer): QuoteUpdateRequestTransfer
    {
        $quoteUpdateRequestTransfer = new QuoteUpdateRequestTransfer();
        $quoteUpdateRequestTransfer->setIdQuote($quoteTransfer->getIdQuote());
        $quoteUpdateRequestTransfer->setCustomer($quoteTransfer->getCustomer());
        $quoteUpdateRequestAttributesTransfer = new QuoteUpdateRequestAttributesTransfer();
        $quoteUpdateRequestTransfer->setQuoteUpdateRequestAttributes($quoteUpdateRequestAttributesTransfer);

        return $quoteUpdateRequestTransfer;
    }
}
