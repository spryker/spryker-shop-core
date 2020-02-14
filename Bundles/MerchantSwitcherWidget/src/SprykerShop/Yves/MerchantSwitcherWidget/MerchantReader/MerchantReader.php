<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\MerchantSwitcherWidget\MerchantReader;

use SprykerShop\Yves\MerchantSwitcherWidget\Cookie\SelectedMerchantCookieInterface;
use SprykerShop\Yves\MerchantSwitcherWidget\Dependency\Client\MerchantSwitcherWidgetToMerchantSearchClientInterface;

class MerchantReader implements MerchantReaderInterface
{
    /**
     * @var \SprykerShop\Yves\MerchantSwitcherWidget\Dependency\Client\MerchantSwitcherWidgetToMerchantSearchClientInterface
     */
    protected $merchantSearchClient;

    /**
     * @var \SprykerShop\Yves\MerchantSwitcherWidget\Cookie\SelectedMerchantCookieInterface
     */
    protected $merchantCookie;

    /**
     * @param \SprykerShop\Yves\MerchantSwitcherWidget\Dependency\Client\MerchantSwitcherWidgetToMerchantSearchClientInterface $merchantSearchClient
     * @param \SprykerShop\Yves\MerchantSwitcherWidget\Cookie\SelectedMerchantCookieInterface $merchantCookie
     */
    public function __construct(
        MerchantSwitcherWidgetToMerchantSearchClientInterface $merchantSearchClient,
        SelectedMerchantCookieInterface $merchantCookie
    ) {
        $this->merchantSearchClient = $merchantSearchClient;
        $this->merchantCookie = $merchantCookie;
    }

    /**
     * @return string
     */
    public function getSelectedMerchantReference(): string
    {
        $selectedMerchantReference = $this->merchantCookie->getMerchantSelector();
        $merchantTransfers = $this->merchantSearchClient->getActiveMerchants()->getMerchants();

        foreach ($merchantTransfers as $merchantTransfer) {
            if ($selectedMerchantReference === $merchantTransfer->getMerchantReference()) {
                return $selectedMerchantReference;
            }
        }
        /** @var \Generated\Shared\Transfer\MerchantTransfer|false $selectedMerchantTransfer */
        $selectedMerchantTransfer = reset($merchantTransfers);

        if (!$selectedMerchantTransfer) {
            if ($selectedMerchantReference) {
                $this->merchantCookie->removeMerchantSelector();
            }

            return '';
        }

        $selectedMerchantReference = $selectedMerchantTransfer->getMerchantReference();
        $this->merchantCookie->setMerchantSelector($selectedMerchantReference);

        return $selectedMerchantReference;
    }
}
