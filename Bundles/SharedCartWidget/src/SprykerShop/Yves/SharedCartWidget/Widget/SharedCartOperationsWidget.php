<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\SharedCartWidget\Widget;

use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Client\SharedCart\Plugin\ReadSharedCartPermissionPlugin;
use Spryker\Client\SharedCart\Plugin\WriteSharedCartPermissionPlugin;
use Spryker\Yves\Kernel\PermissionAwareTrait;
use Spryker\Yves\Kernel\Widget\AbstractWidget;

/**
 * @method \SprykerShop\Yves\SharedCartWidget\SharedCartWidgetFactory getFactory()
 */
class SharedCartOperationsWidget extends AbstractWidget
{
    use PermissionAwareTrait;

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     */
    public function __construct(QuoteTransfer $quoteTransfer)
    {
        $customerTransfer = $this->getFactory()->getCustomerClient()->getCustomer();
        $this
            ->addParameter('cart', $quoteTransfer)
            ->addParameter('actions', $this->getCartActions($quoteTransfer, $customerTransfer))
            ->addParameter('isQuoteOwner', $this->isQuoteOwner($quoteTransfer, $customerTransfer))
            ->addParameter('isSharedCartAllowed', $this->isSharedCartAllowed($customerTransfer));
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return array
     */
    protected function getCartActions(QuoteTransfer $quoteTransfer, CustomerTransfer $customerTransfer): array
    {
        $writeAllowed = $this->can(WriteSharedCartPermissionPlugin::KEY, $quoteTransfer->getIdQuote());
        $viewAllowed = $this->can(ReadSharedCartPermissionPlugin::KEY, $quoteTransfer->getIdQuote()) || $writeAllowed;

        return [
            'view' => $viewAllowed,
            'update' => $writeAllowed,
            'set_default' => $viewAllowed && !$quoteTransfer->getIsDefault(),
            'duplicate' => $writeAllowed,
            'clear' => $writeAllowed,
            'delete' => $writeAllowed && $this->isDeleteCartAllowed($quoteTransfer, $customerTransfer),
        ];
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return bool
     */
    protected function isQuoteOwner(QuoteTransfer $quoteTransfer, CustomerTransfer $customerTransfer): bool
    {
        return strcmp($customerTransfer->getCustomerReference(), $quoteTransfer->getCustomerReference()) === 0;
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return bool
     */
    protected function isSharedCartAllowed(CustomerTransfer $customerTransfer): bool
    {
        if ($customerTransfer->getCompanyUserTransfer()) {
            return true;
        }

        return false;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $currentQuoteTransfer
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return bool
     */
    protected function isDeleteCartAllowed(QuoteTransfer $currentQuoteTransfer, CustomerTransfer $customerTransfer): bool
    {
        $ownedQuoteNumber = 0;
        foreach ($this->getFactory()->getMultiCartClient()->getQuoteCollection()->getQuotes() as $quoteTransfer) {
            if ($this->isQuoteOwner($quoteTransfer, $customerTransfer)) {
                $ownedQuoteNumber++;
            }
        }

        return $ownedQuoteNumber > 1 || (!$this->isQuoteOwner($currentQuoteTransfer, $customerTransfer) && $ownedQuoteNumber > 0);
    }

    /**
     * Specification:
     * - Returns the name of the widget as it's used in templates.
     *
     * @api
     *
     * @return string
     */
    public static function getName(): string
    {
        return 'SharedCartOperationsWidget';
    }

    /**
     * Specification:
     * - Returns the the template file path to render the widget.
     *
     * @api
     *
     * @return string
     */
    public static function getTemplate(): string
    {
        return '@SharedCartWidget/views/shared-cart-operations/shared-cart-operations.twig';
    }
}
