<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ServicePointCartPage\MessageAdder;

use ArrayObject;
use SprykerShop\Yves\ServicePointCartPage\Dependency\Client\ServicePointCartPageToGlossaryStorageClientInterface;
use SprykerShop\Yves\ServicePointCartPage\Dependency\Client\ServicePointCartPageToLocaleClientInterface;
use SprykerShop\Yves\ServicePointCartPage\Dependency\Client\ServicePointCartPageToMessengerClientInterface;

class MessageAdder implements MessageAdderInterface
{
    /**
     * @var \SprykerShop\Yves\ServicePointCartPage\Dependency\Client\ServicePointCartPageToGlossaryStorageClientInterface
     */
    protected ServicePointCartPageToGlossaryStorageClientInterface $glossaryStorageClient;

    /**
     * @var \SprykerShop\Yves\ServicePointCartPage\Dependency\Client\ServicePointCartPageToMessengerClientInterface
     */
    protected ServicePointCartPageToMessengerClientInterface $messengerClient;

    /**
     * @var \SprykerShop\Yves\ServicePointCartPage\Dependency\Client\ServicePointCartPageToLocaleClientInterface
     */
    protected ServicePointCartPageToLocaleClientInterface $localeClient;

    /**
     * @param \SprykerShop\Yves\ServicePointCartPage\Dependency\Client\ServicePointCartPageToGlossaryStorageClientInterface $glossaryStorageClient
     * @param \SprykerShop\Yves\ServicePointCartPage\Dependency\Client\ServicePointCartPageToMessengerClientInterface $messengerClient
     * @param \SprykerShop\Yves\ServicePointCartPage\Dependency\Client\ServicePointCartPageToLocaleClientInterface $localeClient
     */
    public function __construct(
        ServicePointCartPageToGlossaryStorageClientInterface $glossaryStorageClient,
        ServicePointCartPageToMessengerClientInterface $messengerClient,
        ServicePointCartPageToLocaleClientInterface $localeClient
    ) {
        $this->glossaryStorageClient = $glossaryStorageClient;
        $this->messengerClient = $messengerClient;
        $this->localeClient = $localeClient;
    }

    /**
     * @param \ArrayObject<int, \Generated\Shared\Transfer\QuoteErrorTransfer> $quoteErrorTransfers
     *
     * @return void
     */
    public function addQuoteResponseErrors(ArrayObject $quoteErrorTransfers): void
    {
        $currentLocale = $this->localeClient->getCurrentLocale();
        foreach ($quoteErrorTransfers as $quoteErrorTransfer) {
            $translatedErrorMessage = $this->glossaryStorageClient
                ->translate(
                    $quoteErrorTransfer->getMessageOrFail(),
                    $currentLocale,
                    $quoteErrorTransfer->getParameters(),
                );

            $this->messengerClient->addErrorMessage($translatedErrorMessage);
        }
    }
}
