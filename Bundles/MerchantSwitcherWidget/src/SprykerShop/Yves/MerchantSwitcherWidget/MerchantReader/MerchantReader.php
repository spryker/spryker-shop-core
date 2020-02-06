<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\MerchantSwitcherWidget\MerchantReader;

use Generated\Shared\Transfer\MerchantCollectionTransfer;
use Generated\Shared\Transfer\MerchantTransfer;
use SprykerShop\Yves\MerchantSwitcherWidget\Dependency\Client\MerchantSwitcherWidgetToMerchantSearchClientInterface;
use SprykerShop\Yves\MerchantSwitcherWidget\MerchantSwitcherWidgetConfig;
use Symfony\Component\HttpFoundation\Request;

class MerchantReader implements MerchantReaderInterface
{
    /**
     * @var \SprykerShop\Yves\MerchantSwitcherWidget\Dependency\Client\MerchantSwitcherWidgetToMerchantSearchClientInterface
     */
    protected $merchantSearchClient;

    /**
     * @var \Symfony\Component\HttpFoundation\Request
     */
    protected $request;

    /**
     * @var \SprykerShop\Yves\MerchantSwitcherWidget\MerchantSwitcherWidgetConfig
     */
    protected $merchantSwitcherWidgetConfig;

    /**
     * @param \SprykerShop\Yves\MerchantSwitcherWidget\Dependency\Client\MerchantSwitcherWidgetToMerchantSearchClientInterface $merchantSearchClient
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \SprykerShop\Yves\MerchantSwitcherWidget\MerchantSwitcherWidgetConfig $merchantSwitcherWidgetConfig
     */
    public function __construct(
        MerchantSwitcherWidgetToMerchantSearchClientInterface $merchantSearchClient,
        Request $request,
        MerchantSwitcherWidgetConfig $merchantSwitcherWidgetConfig
    ) {
        $this->merchantSearchClient = $merchantSearchClient;
        $this->request = $request;
        $this->merchantSwitcherWidgetConfig = $merchantSwitcherWidgetConfig;
    }

    /**
     * @return \Generated\Shared\Transfer\MerchantCollectionTransfer
     */
    public function getActiveMerchants(): MerchantCollectionTransfer
    {
        return $this->merchantSearchClient->getActiveMerchants();
    }

    /**
     * @return string
     */
    public function getSelectedMerchantReference(): string
    {
        $selectedMerchantReference = $this->request->cookies->get($this->merchantSwitcherWidgetConfig->getMerchantSelectorCookieIdentifier());

        if ($selectedMerchantReference) {
            return $selectedMerchantReference;
        }

        /** @var \Generated\Shared\Transfer\MerchantTransfer $selectedMerchantTransfer */
        $selectedMerchantTransfer = $this->getActiveMerchants()->getMerchants()->getIterator()->current();

        if (!$selectedMerchantTransfer instanceof MerchantTransfer) {
            return '';
        }

        $selectedMerchantTransfer->requireMerchantReference();

        return $selectedMerchantTransfer->getMerchantReference();
    }
}
