<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\SharedCartWidget\Widget;

use ArrayObject;
use Generated\Shared\Transfer\CustomerCollectionTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Yves\Kernel\Widget\AbstractWidget;

/**
 * @method \SprykerShop\Yves\SharedCartWidget\SharedCartWidgetFactory getFactory()
 */
class CartDeleteSharingCompanyUsersListWidget extends AbstractWidget
{
    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     */
    public function __construct(QuoteTransfer $quoteTransfer)
    {
        $this->addCustomerCollectionParameter($quoteTransfer);
    }

    /**
     * @return string
     */
    public static function getName(): string
    {
        return 'CartDeleteSharingCompanyUsersListWidget';
    }

    /**
     * @return string
     */
    public static function getTemplate(): string
    {
        return '@SharedCartWidget/views/shared-cart-sharing-users/shared-cart-sharing-users.twig';
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    protected function addCustomerCollectionParameter(QuoteTransfer $quoteTransfer): void
    {
        $customerCollectionTransfer = $this->getFactory()
            ->getSharedCartClient()
            ->getCustomerCollectionByQuote($quoteTransfer);
        $customerCollectionTransfer = $this->excludeCustomerFromCustomerCollection(
            $customerCollectionTransfer,
            $quoteTransfer->getCustomer()
        );

        $this->addParameter(
            'customerCollection',
            $customerCollectionTransfer->getCustomers()
        );
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerCollectionTransfer $customerCollectionTransfer
     * @param \Generated\Shared\Transfer\CustomerTransfer $excludingCustomerTransfer
     *
     * @return \Generated\Shared\Transfer\CustomerCollectionTransfer
     */
    protected function excludeCustomerFromCustomerCollection(
        CustomerCollectionTransfer $customerCollectionTransfer,
        CustomerTransfer $excludingCustomerTransfer
    ): CustomerCollectionTransfer {
        $customersCollection = new ArrayObject();
        foreach ($customerCollectionTransfer->getCustomers() as $customerTransfer) {
            if ($customerTransfer->getCustomerReference() === $excludingCustomerTransfer->getCustomerReference()) {
                continue;
            }

            $customersCollection->append($customerTransfer);
        }

        return $customerCollectionTransfer->setCustomers($customersCollection);
    }
}
