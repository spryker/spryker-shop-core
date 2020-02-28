<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\MerchantSwitcherWidget\MerchantSwitcher;

use Generated\Shared\Transfer\MerchantSwitchRequestTransfer;
use SprykerShop\Yves\MerchantSwitcherWidget\Dependency\Client\MerchantSwitcherWidgetToMerchantSwitcherClientInterface;
use SprykerShop\Yves\MerchantSwitcherWidget\Dependency\Client\MerchantSwitcherWidgetToQuoteClientInterface;

class MerchantSwitcher implements MerchantSwitcherInterface
{
    /**
     * @var \SprykerShop\Yves\MerchantSwitcherWidget\Dependency\Client\MerchantSwitcherWidgetToQuoteClientInterface
     */
    protected $quoteClient;

    /**
     * @var \SprykerShop\Yves\MerchantSwitcherWidget\Dependency\Client\MerchantSwitcherWidgetToMerchantSwitcherClientInterface
     */
    protected $merchantSwitcherClient;

    /**
     * @param \SprykerShop\Yves\MerchantSwitcherWidget\Dependency\Client\MerchantSwitcherWidgetToQuoteClientInterface $quoteClient
     * @param \SprykerShop\Yves\MerchantSwitcherWidget\Dependency\Client\MerchantSwitcherWidgetToMerchantSwitcherClientInterface $merchantSwitcherClient
     */
    public function __construct(
        MerchantSwitcherWidgetToQuoteClientInterface $quoteClient,
        MerchantSwitcherWidgetToMerchantSwitcherClientInterface $merchantSwitcherClient
    ) {
        $this->quoteClient = $quoteClient;
        $this->merchantSwitcherClient = $merchantSwitcherClient;
    }

    /**
     * @param string $merchantReference
     *
     * @return void
     */
    public function switchMerchantInQuote(string $merchantReference): void
    {
        $quoteTransfer = $this->quoteClient->getQuote();

        if ($quoteTransfer->getMerchantReference() === $merchantReference) {
            return;
        }

        $merchantSwitchRequestTransfer = new MerchantSwitchRequestTransfer();
        $merchantSwitchRequestTransfer->setMerchantReference($merchantReference);
        $merchantSwitchRequestTransfer->setQuote($quoteTransfer);

        $quoteTransfer = $this->merchantSwitcherClient->switchMerchantInQuote($merchantSwitchRequestTransfer)->getQuote();

        $this->quoteClient->setQuote($quoteTransfer);
    }
}
