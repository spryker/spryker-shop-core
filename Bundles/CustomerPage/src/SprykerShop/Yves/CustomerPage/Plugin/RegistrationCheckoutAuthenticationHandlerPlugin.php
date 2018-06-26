<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CustomerPage\Plugin;

use Generated\Shared\Transfer\CustomerResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Yves\Kernel\AbstractPlugin;

/**
 * @method \SprykerShop\Yves\CustomerPage\CustomerPageFactory getFactory()
 */
class RegistrationCheckoutAuthenticationHandlerPlugin extends AbstractPlugin implements CheckoutAuthenticationHandlerPluginInterface
{
    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function addToQuote(QuoteTransfer $quoteTransfer)
    {
        $customerResponseTransfer = $this->getFactory()
            ->getAuthenticationHandler()
            ->registerCustomer($quoteTransfer->getCustomer());

        $this->processErrorMessages($customerResponseTransfer);

        if ($customerResponseTransfer->getIsSuccess() === true) {
            $quoteTransfer = $this->getQuoteClient()->getQuote();
            $quoteTransfer->setCustomer($customerResponseTransfer->getCustomerTransfer());
        }

        return $quoteTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerResponseTransfer $customerResponseTransfer
     *
     * @return void
     */
    protected function processErrorMessages(CustomerResponseTransfer $customerResponseTransfer)
    {
        foreach ($customerResponseTransfer->getErrors() as $errorTransfer) {
            $this->getMessenger()->addErrorMessage($errorTransfer->getMessage());
        }
    }

    /**
     * @return \Spryker\Yves\Messenger\FlashMessenger\FlashMessengerInterface
     */
    protected function getMessenger()
    {
        return $this->getFactory()->getMessenger();
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    public function canHandle(QuoteTransfer $quoteTransfer)
    {
        return ($quoteTransfer->getCustomer() !== null);
    }

    /**
     * @return \SprykerShop\Yves\CustomerPage\Dependency\Client\CustomerPageToQuoteClientInteface
     */
    public function getQuoteClient()
    {
        return $this->getFactory()->getQuoteClient();
    }
}
