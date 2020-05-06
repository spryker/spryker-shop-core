<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\QuoteRequestAgentPage\Form\DataProvider;

use Generated\Shared\Transfer\QuoteRequestTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use SprykerShop\Yves\QuoteRequestAgentPage\Dependency\Client\QuoteRequestAgentPageToCartClientInterface;
use SprykerShop\Yves\QuoteRequestAgentPage\Dependency\Client\QuoteRequestAgentPageToPriceClientInterface;
use SprykerShop\Yves\QuoteRequestAgentPage\Form\QuoteRequestAgentForm;
use SprykerShop\Yves\QuoteRequestAgentPage\Grouper\ShipmentGrouperInterface;

class QuoteRequestAgentFormDataProvider
{
    /**
     * @var \SprykerShop\Yves\QuoteRequestAgentPage\Dependency\Client\QuoteRequestAgentPageToCartClientInterface
     */
    protected $cartClient;

    /**
     * @var \SprykerShop\Yves\QuoteRequestAgentPage\Dependency\Client\QuoteRequestAgentPageToPriceClientInterface
     */
    protected $priceClient;

    /**
     * @var \SprykerShop\Yves\QuoteRequestAgentPage\Grouper\ShipmentGrouperInterface
     */
    protected $shipmentGrouper;

    /**
     * @param \SprykerShop\Yves\QuoteRequestAgentPage\Dependency\Client\QuoteRequestAgentPageToCartClientInterface $cartClient
     * @param \SprykerShop\Yves\QuoteRequestAgentPage\Dependency\Client\QuoteRequestAgentPageToPriceClientInterface $priceClient
     * @param \SprykerShop\Yves\QuoteRequestAgentPage\Grouper\ShipmentGrouperInterface $shipmentGrouper
     */
    public function __construct(
        QuoteRequestAgentPageToCartClientInterface $cartClient,
        QuoteRequestAgentPageToPriceClientInterface $priceClient,
        ShipmentGrouperInterface $shipmentGrouper
    ) {
        $this->cartClient = $cartClient;
        $this->priceClient = $priceClient;
        $this->shipmentGrouper = $shipmentGrouper;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteRequestTransfer $quoteRequestTransfer
     *
     * @return array
     */
    public function getOptions(QuoteRequestTransfer $quoteRequestTransfer): array
    {
        $quoteTransfer = $quoteRequestTransfer
            ->requireLatestVersion()
            ->getLatestVersion()
                ->requireQuote()
                ->getQuote();

        return [
            QuoteRequestAgentForm::OPTION_PRICE_MODE => $this->getPriceMode($quoteTransfer),
            QuoteRequestAgentForm::OPTION_IS_QUOTE_VALID => $this->isQuoteValid($quoteTransfer),
            QuoteRequestAgentForm::OPTION_SHIPMENT_GROUPS => $this->shipmentGrouper->groupItemsByShippingMethod($quoteTransfer),
        ];
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return string
     */
    protected function getPriceMode(QuoteTransfer $quoteTransfer): string
    {
        return $quoteTransfer->getPriceMode() ?? $this->priceClient->getCurrentPriceMode();
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    protected function isQuoteValid(QuoteTransfer $quoteTransfer): bool
    {
        if (!$quoteTransfer->getItems()->count()) {
            return true;
        }

        return $this->cartClient->validateSpecificQuote($quoteTransfer)
            ->getIsSuccessful();
    }
}
