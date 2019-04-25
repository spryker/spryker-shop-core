<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\PersistentCartShareWidget\Widget;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Yves\Kernel\Widget\AbstractWidget;

/**
 * @method \SprykerShop\Yves\PersistentCartShareWidget\PersistentCartShareWidgetFactory getFactory()
 */
class ShareCartByLinkWidget extends AbstractWidget
{
    protected const CART_PARAMETER = 'cart';
    protected const IS_QUOTE_OWNER_PARAMETER = 'isQuoteOwner';
    protected const CART_SHARE_OPTIONS_PARAMETER = 'cartShareOptions';

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     */
    public function __construct(QuoteTransfer $quoteTransfer)
    {
        $this->addCartParameter($quoteTransfer);
        $this->addIsQuoteOwnerParameter($quoteTransfer);
        $this->addCartShareOptionsParameter();
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    protected function addCartParameter(QuoteTransfer $quoteTransfer): void
    {
        $this->addParameter(static::CART_PARAMETER, $quoteTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    protected function addIsQuoteOwnerParameter(QuoteTransfer $quoteTransfer): void
    {
        $this->addParameter(static::IS_QUOTE_OWNER_PARAMETER, $this->isQuoteOwner($quoteTransfer));
    }

    /**
     * @return void
     */
    protected function addCartShareOptionsParameter(): void
    {
        $this->addParameter(static::CART_SHARE_OPTIONS_PARAMETER, $this->getCartShareOptionsGroups());
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    protected function isQuoteOwner(QuoteTransfer $quoteTransfer): bool
    {
        $customer = $this->getFactory()->getCustomerClient()->getCustomer();
        return $customer->getCustomerReference() === $quoteTransfer->getCustomerReference();
    }

    /**
     * @return string
     */
    public static function getName(): string
    {
        return 'ShareCartByLinkWidget';
    }

    /**
     * @return string
     */
    public static function getTemplate(): string
    {
        return '@PersistentCartShareWidget/views/share-cart-by-link-widget/share-cart-by-link-widget.twig';
    }

    /**
     * @return array
     */
    protected function getCartShareOptionsGroups(): array
    {
        return $this->getFactory()->getPersistentCartShareHelper()->generateCartShareOptionGroups();
    }
}
