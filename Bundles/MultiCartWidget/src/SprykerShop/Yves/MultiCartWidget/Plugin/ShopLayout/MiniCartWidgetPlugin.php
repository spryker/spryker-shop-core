<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\MultiCartWidget\Plugin\ShopLayout;

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
        $activeQuoteTransfer = $this->getActiveCart();

        $this
            ->addParameter('cartQuantity', $cartQuantity)
            ->addParameter('activeCart', $activeQuoteTransfer)
            ->addParameter('cartList', $this->getInActiveQuoteList($activeQuoteTransfer))
            ->addParameter('isMultiCartAllowed', $this->isMultiCartAllowed());
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

    /**
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function getActiveCart(): QuoteTransfer
    {
        return $this->getFactory()->getMultiCartClient()->getDefaultCart();
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $activeQuoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer[]
     */
    protected function getInActiveQuoteList(QuoteTransfer $activeQuoteTransfer): array
    {
        $quoteCollectionTransfer = $this->getFactory()
            ->getMultiCartClient()
            ->getQuoteCollection();

        $inActiveQuoteTransferList = [];
        foreach ($quoteCollectionTransfer->getQuotes() as $quoteTransfer) {
            if ($quoteTransfer->getIdQuote() !== $activeQuoteTransfer->getIdQuote()) {
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
