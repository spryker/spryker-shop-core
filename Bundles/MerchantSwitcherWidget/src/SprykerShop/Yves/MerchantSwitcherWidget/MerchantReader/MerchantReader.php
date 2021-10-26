<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\MerchantSwitcherWidget\MerchantReader;

use ArrayObject;
use Generated\Shared\Transfer\MerchantSearchRequestTransfer;
use SprykerShop\Yves\MerchantSwitcherWidget\Cookie\SelectedMerchantCookieInterface;
use SprykerShop\Yves\MerchantSwitcherWidget\Dependency\Client\MerchantSwitcherWidgetToMerchantSearchClientInterface;
use SprykerShop\Yves\MerchantSwitcherWidget\MerchantSwitcher\MerchantSwitcherInterface;

class MerchantReader implements MerchantReaderInterface
{
    /**
     * @uses \Spryker\Client\MerchantSearch\Plugin\Elasticsearch\ResultFormatter\MerchantSearchResultFormatterPlugin::NAME
     *
     * @var string
     */
    protected const MERCHANT_SEARCH_COLLECTION = 'MerchantSearchCollection';

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
        $merchantSearchTransfers = $this->getMerchantSearchTransfers();

        /** @var \Generated\Shared\Transfer\MerchantSearchTransfer $merchantSearchTransfer */
        foreach ($merchantSearchTransfers as $merchantSearchTransfer) {
            if ($selectedMerchantReference === $merchantSearchTransfer->getMerchantReference()) {
                $this->merchantSwitcher->switchMerchantInQuote($selectedMerchantReference);

                return $selectedMerchantReference;
            }
        }

        $merchantSearchTransfers->getIterator()->rewind();

        if (!$merchantSearchTransfers->count()) {
            if ($selectedMerchantReference) {
                $this->selectedMerchantCookie->removeMerchantReference();
            }

            return null;
        }
        /** @var \Generated\Shared\Transfer\MerchantSearchTransfer $merchantSearchTransfer */
        $merchantSearchTransfer = $merchantSearchTransfers->getIterator()->current();

        $selectedMerchantReference = $merchantSearchTransfer->getMerchantReference();
        $this->selectedMerchantCookie->setMerchantReference($selectedMerchantReference);
        $this->merchantSwitcher->switchMerchantInQuote($selectedMerchantReference);

        return $selectedMerchantReference;
    }

    /**
     * @return \ArrayObject<int, \Generated\Shared\Transfer\MerchantSearchTransfer>
     */
    public function getMerchantSearchTransfers(): ArrayObject
    {
        /**
         * @var \Generated\Shared\Transfer\MerchantSearchCollectionTransfer $merchantSearchCollectionTransfer
         */
        $merchantSearchCollectionTransfer = $this->merchantSearchClient
            ->search(new MerchantSearchRequestTransfer())[static::MERCHANT_SEARCH_COLLECTION];

        return $merchantSearchCollectionTransfer
            ->getMerchants();
    }
}
