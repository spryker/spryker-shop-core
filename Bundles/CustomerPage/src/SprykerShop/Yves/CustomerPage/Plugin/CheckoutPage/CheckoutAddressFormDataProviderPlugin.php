<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CustomerPage\Plugin\CheckoutPage;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Shared\Kernel\Transfer\AbstractTransfer;
use Spryker\Yves\Kernel\AbstractPlugin;
use Spryker\Yves\StepEngine\Dependency\Form\StepEngineFormDataProviderInterface;

/**
 * @method \SprykerShop\Yves\CustomerPage\CustomerPageFactory getFactory()
 */
class CheckoutAddressFormDataProviderPlugin extends AbstractPlugin implements StepEngineFormDataProviderInterface
{
    /**
     * {@inheritDoc}
     *  - Provides data for CheckoutAddressCollectionForm.
     *
     * @api
     *
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function getData(AbstractTransfer $quoteTransfer): QuoteTransfer
    {
        return $this->getFactory()
            ->createCheckoutAddressFormDataProvider()
            ->getData($quoteTransfer);
    }

    /**
     * {@inheritDoc}
     *  - Provides options for CheckoutAddressCollectionForm.
     *
     * @api
     *
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer $quoteTransfer
     *
     * @return array
     */
    public function getOptions(AbstractTransfer $quoteTransfer): array
    {
        return $this->getFactory()
            ->createCheckoutAddressFormDataProvider()
            ->getOptions($quoteTransfer);
    }
}
