<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\MerchantSwitcherWidget\MerchantReader;

use SprykerShop\Yves\MerchantSwitcherWidget\Cookie\SelectedMerchantCookieInterface;
use SprykerShop\Yves\MerchantSwitcherWidget\Dependency\Client\MerchantSwitcherWidgetToMerchantSearchClientInterface;
use SprykerShop\Yves\MerchantSwitcherWidget\MerchantSwitcher\MerchantSwitcherInterface;

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
     * @var \SprykerShop\Yves\MerchantSwitcherWidget\MerchantSwitcher\MerchantSwitcherInterface
     */
    protected $merchantSwitcher;

    /**
     * @param \SprykerShop\Yves\MerchantSwitcherWidget\Dependency\Client\MerchantSwitcherWidgetToMerchantSearchClientInterface $merchantSearchClient
     * @param \SprykerShop\Yves\MerchantSwitcherWidget\Cookie\SelectedMerchantCookieInterface $selectedMerchantCookie
     * @param \SprykerShop\Yves\MerchantSwitcherWidget\MerchantSwitcher\MerchantSwitcherInterface $merchantSwitcher
     */
    public function __construct(
        MerchantSwitcherWidgetToMerchantSearchClientInterface $merchantSearchClient,
        SelectedMerchantCookieInterface $selectedMerchantCookie,
        MerchantSwitcherInterface $merchantSwitcher
    ) {
        $this->merchantSearchClient = $merchantSearchClient;
        $this->selectedMerchantCookie = $selectedMerchantCookie;
        $this->merchantSwitcher = $merchantSwitcher;
    }

    /**
     * @return string|null
     */
    public function extractSelectedMerchantReference(): ?string
    {
        $selectedMerchantReference = $this->selectedMerchantCookie->getMerchantReference();
        $merchantTransfers = $this->merchantSearchClient->getMerchants()->getMerchants();

        foreach ($merchantTransfers as $merchantTransfer) {
            if ($selectedMerchantReference === $merchantTransfer->getMerchantReference()) {
                $this->merchantSwitcher->switchMerchantInQuote($selectedMerchantReference);

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
        $this->merchantSwitcher->switchMerchantInQuote($selectedMerchantReference);

        return $selectedMerchantReference;
    }
}
