<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\MultiCartWidget\Plugin\ShopLayout;

use Generated\Shared\Transfer\QuoteCollectionTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Yves\Kernel\Widget\AbstractWidgetPlugin;
use SprykerShop\Yves\ShopLayoutExtension\Dependency\Plugin\MultiCart\MiniCartWidgetPluginInterface;

/**
 * @method \SprykerShop\Yves\MultiCartWidget\MultiCartWidgetFactory getFactory()
 */
class MiniCartWidgetPlugin extends AbstractWidgetPlugin implements MiniCartWidgetPluginInterface
{
    /**
     * @param int $cartQuantity
     *
     * @return void
     */
    public function initialize($cartQuantity): void
    {
        $quoteCollectionTransfer = $this->getFactory()->getMultiCartClient()->getQuoteCollection();
        $activeQuoteTransfer = $this->getFactory()->getMultiCartClient()->getDefaultCart();
        $this->addParameter('cartQuantity', $cartQuantity);
        $this->addParameter('activeCart', $activeQuoteTransfer);
        $this->addParameter('cartList', $this->getInActiveQuoteList($activeQuoteTransfer, $quoteCollectionTransfer));
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
        return '@MultiCartWidget/views/mini-cart/mini-cart.twig';
    }
}
