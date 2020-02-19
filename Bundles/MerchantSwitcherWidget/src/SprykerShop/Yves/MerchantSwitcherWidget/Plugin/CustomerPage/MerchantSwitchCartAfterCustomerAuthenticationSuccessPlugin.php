<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\MerchantSwitcherWidget\Plugin\CustomerPage;

use Generated\Shared\Transfer\MerchantSwitchRequestTransfer;
use Spryker\Yves\Kernel\AbstractPlugin;
use SprykerShop\Yves\CustomerPageExtension\Dependency\Plugin\AfterCustomerAuthenticationSuccessPluginInterface;

/**
 * @method \SprykerShop\Yves\MerchantSwitcherWidget\MerchantSwitcherWidgetFactory getFactory()
 */
class MerchantSwitchCartAfterCustomerAuthenticationSuccessPlugin extends AbstractPlugin implements AfterCustomerAuthenticationSuccessPluginInterface
{
    /**
     * {@inheritDoc}
     * - Sets merchant reference value to cookies if a customer's quote contains it and the quote is not empty.
     * - If the quote is empty or the quote doesn't contain merchant reference gets merchant reference from cookies and sets to quote.
     *
     * @api
     *
     * @return void
     */
    public function execute(): void
    {
        $quoteTransfer = $this->getFactory()->getQuoteClient()->getQuote();
        $merchantReference = $quoteTransfer->getMerchantReference();

        if ($merchantReference && $quoteTransfer->getItems()->count()) {
            $this->getFactory()->createSelectedMerchantCookie()->setMerchantReference($merchantReference);

            return;
        }

        $merchantReference = $this->getFactory()->createSelectedMerchantCookie()->getMerchantReference();
        $merchantSwitchRequestTransfer = (new MerchantSwitchRequestTransfer())
            ->setQuote($quoteTransfer)
            ->setMerchantReference($merchantReference);

        $this->getFactory()->getMerchantSwitcherClient()->switchMerchant($merchantSwitchRequestTransfer);
    }
}
