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
     * - Switch ItemTransfer.merchantReference for current Quote according to selected merchant reference.
     * - Switch ItemTransfer.offerReference for current Quote according to selected merchant reference.
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
            //set cookie
        }

        //get cookie
        $merchantSwitchRequestTransfer = (new MerchantSwitchRequestTransfer())
            ->setQuote($quoteTransfer)
            ->setMerchantReference($merchantReference);

        $this->getFactory()->getMerchantSwitcherClient()->switchMerchant($merchantSwitchRequestTransfer);
    }
}
