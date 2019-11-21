<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ConfigurableBundleCartNoteWidget\Handler;

use Generated\Shared\Transfer\ConfiguredBundleCartNoteRequestTransfer;
use Generated\Shared\Transfer\QuoteResponseTransfer;
use SprykerShop\Yves\ConfigurableBundleCartNoteWidget\Dependency\Client\ConfigurableBundleCartNoteWidgetToConfigurableBundleCartNoteClientInterface;
use SprykerShop\Yves\ConfigurableBundleCartNoteWidget\Dependency\Client\ConfigurableBundleCartNoteWidgetToQuoteClientInterface;

class ConfigurableBundleCartNoteHandler implements ConfigurableBundleCartNoteHandlerInterface
{
    /**
     * @var \SprykerShop\Yves\ConfigurableBundleCartNoteWidget\Dependency\Client\ConfigurableBundleCartNoteWidgetToConfigurableBundleCartNoteClientInterface
     */
    protected $configurableBundleCartNoteClient;

    /**
     * @var \SprykerShop\Yves\ConfigurableBundleCartNoteWidget\Dependency\Client\ConfigurableBundleCartNoteWidgetToQuoteClientInterface
     */
    protected $quoteClient;

    /**
     * @param \SprykerShop\Yves\ConfigurableBundleCartNoteWidget\Dependency\Client\ConfigurableBundleCartNoteWidgetToConfigurableBundleCartNoteClientInterface $configurableBundleCartNoteClient
     * @param \SprykerShop\Yves\ConfigurableBundleCartNoteWidget\Dependency\Client\ConfigurableBundleCartNoteWidgetToQuoteClientInterface $quoteClient
     */
    public function __construct(
        ConfigurableBundleCartNoteWidgetToConfigurableBundleCartNoteClientInterface $configurableBundleCartNoteClient,
        ConfigurableBundleCartNoteWidgetToQuoteClientInterface $quoteClient
    ) {
        $this->configurableBundleCartNoteClient = $configurableBundleCartNoteClient;
        $this->quoteClient = $quoteClient;
    }

    /**
     * @param \Generated\Shared\Transfer\ConfiguredBundleCartNoteRequestTransfer $configuredBundleCartNoteRequestTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function setCartNoteToConfiguredBundle(
        ConfiguredBundleCartNoteRequestTransfer $configuredBundleCartNoteRequestTransfer
    ): QuoteResponseTransfer {
        $configuredBundleCartNoteRequestTransfer->setQuote(
            $this->quoteClient->getQuote()
        );

        $quoteResponseTransfer = $this->configurableBundleCartNoteClient
            ->setCartNoteToConfiguredBundle($configuredBundleCartNoteRequestTransfer);

        if (!$quoteResponseTransfer->getIsSuccessful()) {
            return $quoteResponseTransfer;
        }

        $this->quoteClient->setQuote($quoteResponseTransfer->getQuoteTransfer());

        return $quoteResponseTransfer;
    }
}
