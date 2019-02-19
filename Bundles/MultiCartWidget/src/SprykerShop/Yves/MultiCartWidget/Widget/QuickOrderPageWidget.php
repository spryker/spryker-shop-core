<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\MultiCartWidget\Widget;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Yves\Kernel\PermissionAwareTrait;
use Spryker\Yves\Kernel\Widget\AbstractWidget;

/**
 * @method \SprykerShop\Yves\MultiCartWidget\MultiCartWidgetFactory getFactory()
 */
class QuickOrderPageWidget extends AbstractWidget
{
    use PermissionAwareTrait;

    public function __construct()
    {
        $this->addParameter('carts', $this->getQuoteList())
            ->addParameter('isMultiCartAllowed', $this->isMultiCartAllowed());
    }

    /**
     * @return string
     */
    public static function getName(): string
    {
        return 'QuickOrderPageWidget';
    }

    /**
     * @return string
     */
    public static function getTemplate(): string
    {
        return '@MultiCartWidget/views/quick-order-page/quick-order-page.twig';
    }

    /**
     * @return \Generated\Shared\Transfer\QuoteTransfer[]
     */
    protected function getQuoteList(): array
    {
        $quoteCollectionTransfer = $this->getFactory()
            ->getMultiCartClient()
            ->getQuoteCollection();

        $quoteTransferCollection = [];
        $defaultQuoteTransfer = $this->getFactory()->getMultiCartClient()->getDefaultCart();
        if ($this->isQuoteEditable($defaultQuoteTransfer)) {
            $quoteTransferCollection[] = $defaultQuoteTransfer;
        }
        foreach ($quoteCollectionTransfer->getQuotes() as $quoteTransfer) {
            if (!$quoteTransfer->getIsDefault() && $this->isQuoteEditable($quoteTransfer)) {
                $quoteTransferCollection[] = $quoteTransfer;
            }
        }

        return $quoteTransferCollection;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    protected function isQuoteEditable(QuoteTransfer $quoteTransfer): bool
    {
        return $this->getFactory()
            ->getQuoteClient()
            ->isQuoteEditable($quoteTransfer);
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
