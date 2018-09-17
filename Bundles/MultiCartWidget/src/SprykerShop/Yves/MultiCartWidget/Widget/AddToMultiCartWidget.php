<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\MultiCartWidget\Widget;

use Spryker\Yves\Kernel\PermissionAwareTrait;
use Spryker\Yves\Kernel\Widget\AbstractWidget;

/**
 * @method \SprykerShop\Yves\MultiCartWidget\MultiCartWidgetFactory getFactory()
 */
class AddToMultiCartWidget extends AbstractWidget
{
    use PermissionAwareTrait;

    /**
     * @param bool $disabled
     */
    public function __construct($disabled)
    {
        $this->addParameter('carts', $this->getQuoteCollection())
            ->addParameter('disabled', $disabled)
            ->addParameter('isMultiCartAllowed', $this->isMultiCartAllowed());
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
        return 'AddToMultiCartWidget';
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
        return '@MultiCartWidget/views/add-to-multi-cart/add-to-multi-cart.twig';
    }

    /**
     * @return \Generated\Shared\Transfer\QuoteTransfer[]
     */
    protected function getQuoteCollection(): array
    {
        $quoteCollectionTransfer = $this->getFactory()
            ->getMultiCartClient()
            ->getQuoteCollection();

        $quoteTransferCollection = [];
        $defaultQuoteTransfer = $this->getFactory()->getMultiCartClient()->getDefaultCart();
        if ($this->can('WriteSharedCartPermissionPlugin', $defaultQuoteTransfer->getIdQuote())) {
            $quoteTransferCollection[] = $defaultQuoteTransfer;
        }
        foreach ($quoteCollectionTransfer->getQuotes() as $quoteTransfer) {
            if (!$quoteTransfer->getIsDefault() && $this->can('WriteSharedCartPermissionPlugin', $quoteTransfer->getIdQuote())) {
                $quoteTransferCollection[] = $quoteTransfer;
            }
        }

        return $quoteTransferCollection;
    }

    /**
     * @return bool
     */
    protected function isMultiCartAllowed(): bool
    {
        return $this->getFactory()
            ->getMultiCartClient()
            ->isMultiCartAllowed();
    }
}
