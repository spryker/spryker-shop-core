<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\SharedCartWidget\Widget;

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
        $this->addSharingQuoteCustomerCollectionParameter($quoteTransfer);
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
    protected function addSharingQuoteCustomerCollectionParameter(QuoteTransfer $quoteTransfer): void
    {
        $this->addParameter(
            'sharingQuoteCustomerCollection',
            $this->getFactory()->getSharedCartClient()->getSharingSameQuoteCustomerCollection($quoteTransfer)->getCustomers()
        );
    }
}
