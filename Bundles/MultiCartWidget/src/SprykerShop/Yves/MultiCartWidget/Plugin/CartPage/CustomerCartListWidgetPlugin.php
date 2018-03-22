<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\MultiCartWidget\Plugin\CartPage;

use Generated\Shared\Transfer\QuoteCollectionTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Yves\Kernel\Widget\AbstractWidgetPlugin;
use SprykerShop\Yves\CartPage\Dependency\Plugin\MultiCartWidget\MultiCartListWidgetPluginInterface;

/**
 * @method \SprykerShop\Yves\MultiCartWidget\MultiCartWidgetFactory getFactory()
 */
class CustomerCartListWidgetPlugin extends AbstractWidgetPlugin implements MultiCartListWidgetPluginInterface
{
    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    public function initialize(QuoteTransfer $quoteTransfer): void
    {
        $quoteCollectionTransfer = $this->getFactory()->getMultiCartClient()->getQuoteCollection();
        $this->addParameter(
            'cartCollection',
            $this->getInActiveQuoteList($quoteTransfer, $quoteCollectionTransfer)
        );
        $this->addParameter('isMultiCartAllowed', $this->getFactory()->getMultiCartClient()->isMultiCartAllowed());
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $activeQuoteTransfer
     * @param \Generated\Shared\Transfer\QuoteCollectionTransfer $quoteCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer[]
     */
    protected function getInActiveQuoteList(QuoteTransfer $activeQuoteTransfer, QuoteCollectionTransfer $quoteCollectionTransfer)
    {
        $inActiveQuoteTransferList = [];
        foreach ($quoteCollectionTransfer->getQuotes() as $quoteTransfer) {
            if ($quoteTransfer->getIdQuote() !== $activeQuoteTransfer->getIdQuote()) {
                $inActiveQuoteTransferList[] = $quoteTransfer;
            }
        }

        return $inActiveQuoteTransferList;
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @return string
     */
    public static function getName()
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
    public static function getTemplate()
    {
        return '@MultiCartWidget/views/customer-cart-list/customer-cart-list.twig';
    }
}
