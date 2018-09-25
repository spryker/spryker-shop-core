<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\SharedCartWidget\Plugin\MultiCartPage;

use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Yves\Kernel\Widget\AbstractWidgetPlugin;
use SprykerShop\Yves\MultiCartPage\Dependency\Plugin\CartListPermissionGroupWidget\CartListPermissionGroupWidgetPluginInterface;

/**
 * @method \SprykerShop\Yves\SharedCartWidget\SharedCartWidgetFactory getFactory()
 */
class CartListPermissionGroupWidgetPlugin extends AbstractWidgetPlugin implements CartListPermissionGroupWidgetPluginInterface
{
    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param bool $isQuoteDeletable
     *
     * @return void
     */
    public function initialize(QuoteTransfer $quoteTransfer, bool $isQuoteDeletable): void
    {
        $customerTransfer = $this->getFactory()->getCustomerClient()->getCustomer();

        $this->addCartParameter($quoteTransfer);
        $this->addAccessTypeParameter($quoteTransfer);
        $this->addIsSharingAllowedParameter($customerTransfer);
        $this->addIsQuoteDeletableParameter($isQuoteDeletable, $quoteTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    protected function addAccessTypeParameter(QuoteTransfer $quoteTransfer): void
    {
        $this->addParameter(
            'accessType',
            $this->getFactory()
                ->getSharedCartClient()
                ->getQuoteAccessLevel($quoteTransfer)
        );
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    protected function addCartParameter(QuoteTransfer $quoteTransfer): void
    {
        $this->addParameter('cart', $quoteTransfer);
    }

    /**
     * @param bool $isQuoteDeletable
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    protected function addIsQuoteDeletableParameter(bool $isQuoteDeletable, QuoteTransfer $quoteTransfer): void
    {
        $this->addParameter(
            'isQuoteDeletable',
            $isQuoteDeletable && $this->getFactory()->getSharedCartClient()->isQuoteDeletable($quoteTransfer)
        );
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return void
     */
    protected function addIsSharingAllowedParameter(CustomerTransfer $customerTransfer): void
    {
        $this->addParameter('isSharingAllowed', $this->isSharingAllowed($customerTransfer));
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return bool
     */
    protected function isSharingAllowed(CustomerTransfer $customerTransfer): bool
    {
        return $customerTransfer->getCompanyUserTransfer() && $customerTransfer->getCompanyUserTransfer()->getIdCompanyUser();
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @return string
     */
    public static function getName(): string
    {
        return static::NAME;
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @return string
     */
    public static function getTemplate(): string
    {
        return '@SharedCartWidget/views/multi-cart-permission/multi-cart-permission.twig';
    }
}
