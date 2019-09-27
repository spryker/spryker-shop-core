<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CustomerPage\Plugin\CheckoutPage;

use Generated\Shared\Transfer\AddressTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Spryker\Yves\Kernel\AbstractPlugin;
use SprykerShop\Yves\CheckoutPageExtension\Dependency\Plugin\AddressTransferExpanderPluginInterface;

/**
 * @method \SprykerShop\Yves\CustomerPage\CustomerPageFactory getFactory()
 */
class CustomerAddressExpanderPlugin extends AbstractPlugin implements AddressTransferExpanderPluginInterface
{
    /**
     * {@inheritDoc}
     * - Expands address transfer with customer address data.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\AddressTransfer $addressTransfer
     * @param \Generated\Shared\Transfer\CustomerTransfer|null $customerTransfer
     *
     * @return \Generated\Shared\Transfer\AddressTransfer
     */
    public function expand(AddressTransfer $addressTransfer, ?CustomerTransfer $customerTransfer): AddressTransfer
    {
        return $this->getFactory()
            ->createCustomerExpander()
            ->expandWithCustomerAddress($addressTransfer, $customerTransfer);
    }
}
