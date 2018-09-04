<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\MultiCartWidget\Widget;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Yves\Kernel\Widget\AbstractWidget;

/**
 * @method \SprykerShop\Yves\MultiCartWidget\MultiCartWidgetFactory getFactory()
 */
class MultiCartListWidget extends AbstractWidget
{
    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     */
    public function __construct(QuoteTransfer $quoteTransfer)
    {
        $this->addParameter('cartCollection', $this->getInactiveQuoteList())
            ->addParameter('isMultiCartAllowed', $this->isMultiCartAllowed())
            ->addParameter('cart', $quoteTransfer)
            ->addParameter('widgetList', $this->getFactory()->getViewExtendWidgetPlugins());
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
        return 'MultiCartListWidget';
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
        return '@MultiCartWidget/views/customer-cart-list/customer-cart-list.twig';
    }

    /**
     * @return \Generated\Shared\Transfer\QuoteTransfer[]
     */
    protected function getInactiveQuoteList()
    {
        $quoteCollectionTransfer = $this->getFactory()
            ->getMultiCartClient()
            ->getQuoteCollection();

        $inActiveQuoteTransferList = [];
        foreach ($quoteCollectionTransfer->getQuotes() as $quoteTransfer) {
            if (!$quoteTransfer->getIsDefault()) {
                $inActiveQuoteTransferList[] = $quoteTransfer;
            }
        }

        return $inActiveQuoteTransferList;
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
