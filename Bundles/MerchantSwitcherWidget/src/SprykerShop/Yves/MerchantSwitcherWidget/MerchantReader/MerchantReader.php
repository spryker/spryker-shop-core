<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\MerchantSwitcherWidget\MerchantReader;

use Generated\Shared\Transfer\MerchantSwitchRequestTransfer;
use SprykerShop\Yves\MerchantSwitcherWidget\Cookie\SelectedMerchantCookieInterface;
use SprykerShop\Yves\MerchantSwitcherWidget\Dependency\Client\MerchantSwitcherWidgetToMerchantSearchClientInterface;
use SprykerShop\Yves\MerchantSwitcherWidget\Dependency\Client\MerchantSwitcherWidgetToMerchantSwitcherClientInterface;
use SprykerShop\Yves\MerchantSwitcherWidget\Dependency\Client\MerchantSwitcherWidgetToQuoteClientInterface;

class MerchantReader implements MerchantReaderInterface
{
    /**
     * @var \SprykerShop\Yves\MerchantSwitcherWidget\Dependency\Client\MerchantSwitcherWidgetToMerchantSearchClientInterface
     */
    protected $merchantSearchClient;

    /**
     * @var \SprykerShop\Yves\MerchantSwitcherWidget\Cookie\SelectedMerchantCookieInterface
     */
    protected $selectedMerchantCookie;

    /**
     * @var \SprykerShop\Yves\MerchantSwitcherWidget\Dependency\Client\MerchantSwitcherWidgetToMerchantSwitcherClientInterface
     */
    protected $merchantSwitcherClient;

    /**
     * @var \SprykerShop\Yves\MerchantSwitcherWidget\Dependency\Client\MerchantSwitcherWidgetToQuoteClientInterface
     */
    protected $quoteClient;

    /**
     * @param \SprykerShop\Yves\MerchantSwitcherWidget\Dependency\Client\MerchantSwitcherWidgetToMerchantSearchClientInterface $merchantSearchClient
     * @param \SprykerShop\Yves\MerchantSwitcherWidget\Cookie\SelectedMerchantCookieInterface $selectedMerchantCookie
     * @param \SprykerShop\Yves\MerchantSwitcherWidget\Dependency\Client\MerchantSwitcherWidgetToMerchantSwitcherClientInterface $merchantSwitcherClient
     * @param \SprykerShop\Yves\MerchantSwitcherWidget\Dependency\Client\MerchantSwitcherWidgetToQuoteClientInterface $quoteClient
     */
    public function __construct(
        MerchantSwitcherWidgetToMerchantSearchClientInterface $merchantSearchClient,
        SelectedMerchantCookieInterface $selectedMerchantCookie,
        MerchantSwitcherWidgetToMerchantSwitcherClientInterface $merchantSwitcherClient,
        MerchantSwitcherWidgetToQuoteClientInterface $quoteClient
    ) {
        $this->merchantSearchClient = $merchantSearchClient;
        $this->selectedMerchantCookie = $selectedMerchantCookie;
        $this->merchantSwitcherClient = $merchantSwitcherClient;
        $this->quoteClient = $quoteClient;
    }

    /**
     * @return string|null
     */
    public function extractSelectedMerchantReference(): ?string
    {
        $selectedMerchantReference = $this->selectedMerchantCookie->getMerchantReference();
        $merchantTransfers = $this->merchantSearchClient->getActiveMerchants()->getMerchants();

        foreach ($merchantTransfers as $merchantTransfer) {
            if ($selectedMerchantReference === $merchantTransfer->getMerchantReference()) {
                $this->switchMerchantInQuote($selectedMerchantReference);

                return $selectedMerchantReference;
            }
        }
        /** @var \Generated\Shared\Transfer\MerchantTransfer|false $selectedMerchantTransfer */
        $selectedMerchantTransfer = reset($merchantTransfers);

        if (!$selectedMerchantTransfer) {
            if ($selectedMerchantReference) {
                $this->selectedMerchantCookie->removeMerchantReference();
            }

            return null;
        }

        $selectedMerchantReference = $selectedMerchantTransfer->getMerchantReference();
        $this->selectedMerchantCookie->setMerchantReference($selectedMerchantReference);

        $this->switchMerchantInQuote($selectedMerchantReference);

        return $selectedMerchantReference;
    }

    /**
     * @param string $merchantReference
     *
     * @return void
     */
    protected function switchMerchantInQuote(string $merchantReference): void
    {
        $quoteTransfer = $this->quoteClient->getQuote();

        if ($quoteTransfer->getMerchantReference() === $merchantReference) {
            return;
        }

        $merchantSwitchRequestTransfer = new MerchantSwitchRequestTransfer();
        $merchantSwitchRequestTransfer->setMerchantReference($merchantReference);
        $merchantSwitchRequestTransfer->setQuote($quoteTransfer);

        $quoteTransfer = $this->merchantSwitcherClient->switchMerchant($merchantSwitchRequestTransfer)->getQuote();

        $this->quoteClient->setQuote($quoteTransfer);
    }
}
