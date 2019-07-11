<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\SharedCartWidget\Widget;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Yves\Kernel\Widget\AbstractWidget;

/**
 * @deprecated Use \SprykerShop\Yves\SharedCartWidget\Widget\CartDeleteSharingCompanyUsersListWidget instead.
 *
 * @method \SprykerShop\Yves\SharedCartWidget\SharedCartWidgetFactory getFactory()
 */
class CartDeleteCompanyUsersListWidget extends AbstractWidget
{
    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     */
    public function __construct(QuoteTransfer $quoteTransfer)
    {
        $this->addQuoteShareDetailsParameter($quoteTransfer);
    }

    /**
     * @return string
     */
    public static function getName(): string
    {
        return 'CartDeleteCompanyUsersListWidget';
    }

    /**
     * @return string
     */
    public static function getTemplate(): string
    {
        return '@SharedCartWidget/views/shared-cart-users/shared-cart-users.twig';
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    protected function addQuoteShareDetailsParameter(QuoteTransfer $quoteTransfer): void
    {
        $this->addParameter(
            'quoteShareDetails',
            $this->getFactory()->getSharedCartClient()->getShareDetailsByIdQuoteAction($quoteTransfer)->getShareDetails()
        );
    }
}
