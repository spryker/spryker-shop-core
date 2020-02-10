<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CustomerPage\Plugin;

use Spryker\Shared\Kernel\Transfer\AbstractTransfer;
use Spryker\Yves\Kernel\AbstractPlugin;
use Spryker\Yves\StepEngine\Dependency\Plugin\Handler\StepHandlerPluginInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \SprykerShop\Yves\CustomerPage\CustomerPageFactory getFactory()
 */
class CustomerStepHandler extends AbstractPlugin implements StepHandlerPluginInterface
{
    /**
     * @var \SprykerShop\Yves\CustomerPage\Plugin\CheckoutAuthenticationHandlerPluginInterface[]
     */
    protected $authenticationHandlerPlugins;

    public function __construct()
    {
        $this->authenticationHandlerPlugins = $this
            ->getFactory()
            ->getCustomerAuthenticationHandlerPlugins();
    }

    /**
     * Iterate through the authentication handler plugin stack and handle authentication with the first one which can
     * handle the request. The QuoteTransfer input parameter should contain the request. The handler plugin will update
     * the quote with the appropriate CustomerTransfer and return it, e.g. the output QuoteTransfer should contain
     * the response.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function addToDataClass(Request $request, AbstractTransfer $quoteTransfer)
    {
        foreach ($this->authenticationHandlerPlugins as $authHandlerPlugin) {
            if ($authHandlerPlugin->canHandle($quoteTransfer)) {
                $quoteTransfer = $authHandlerPlugin->addToQuote($quoteTransfer);

                break;
            }
        }

        return $quoteTransfer;
    }
}
