<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\MerchantSwitcherWidget\MerchantReader;

use ArrayObject;
use Generated\Shared\Transfer\MerchantTransfer;
use SprykerShop\Yves\MerchantSwitcherWidget\Cookie\MerchantCookieInterface;
use SprykerShop\Yves\MerchantSwitcherWidget\Dependency\Client\MerchantSwitcherWidgetToMerchantSearchClientInterface;

class MerchantReader implements MerchantReaderInterface
{
    /**
     * @var \SprykerShop\Yves\MerchantSwitcherWidget\Dependency\Client\MerchantSwitcherWidgetToMerchantSearchClientInterface
     */
    protected $merchantSearchClient;

    /**
     * @var \SprykerShop\Yves\MerchantSwitcherWidget\Cookie\MerchantCookieInterface
     */
    protected $merchantCookie;

    /**
     * @param \SprykerShop\Yves\MerchantSwitcherWidget\Dependency\Client\MerchantSwitcherWidgetToMerchantSearchClientInterface $merchantSearchClient
     * @param \SprykerShop\Yves\MerchantSwitcherWidget\Cookie\MerchantCookieInterface $merchantCookie
     */
    public function __construct(
        MerchantSwitcherWidgetToMerchantSearchClientInterface $merchantSearchClient,
        MerchantCookieInterface $merchantCookie
    ) {
        $this->merchantSearchClient = $merchantSearchClient;
        $this->merchantCookie = $merchantCookie;
    }

    /**
     * @return \ArrayObject|\Generated\Shared\Transfer\MerchantTransfer[]
     */
    public function getActiveMerchants(): ArrayObject
    {
        return $this->merchantSearchClient->getActiveMerchants()->getMerchants();
    }

    /**
     * @return string
     */
    public function getSelectedMerchantReference(): string
    {
        $selectedMerchantReference = $this->merchantCookie->getMerchantSelectorCookieIdentifier();
        $merchantTransfers = $this->getActiveMerchants();

        foreach ($merchantTransfers as $merchantTransfer) {
            if ($selectedMerchantReference === $merchantTransfer->getMerchantReference()) {
                return $selectedMerchantReference;
            }
        }
        /** @var \Generated\Shared\Transfer\MerchantTransfer $selectedMerchantTransfer */
        $selectedMerchantTransfer = $merchantTransfers->getIterator()->current();

        if (!$selectedMerchantTransfer instanceof MerchantTransfer) {
            if ($selectedMerchantReference) {
                $this->merchantCookie->removeMerchantSelectorCookieIdentifier();
            }

            return '';
        }

        $selectedMerchantReference = $selectedMerchantTransfer->getMerchantReference();
        $this->merchantCookie->setMerchantSelectorCookieIdentifier($selectedMerchantReference);

        return $selectedMerchantReference;
    }
}
