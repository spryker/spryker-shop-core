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
     * @return void
     */
    public function initialize(): void
    {
        $quoteCollectionTransfer = $this->getFactory()->getMultiCartClient()->getQuoteCollection();
        $this->addParameter('cartList', $quoteCollectionTransfer->getQuotes());
        $this->addParameter('activeCart', $this->findActiveCart($quoteCollectionTransfer));
        $this->addParameter('isMultiCartAllowed', $this->getFactory()->getMultiCartClient()->isMultiCartAllowed());
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteCollectionTransfer $quoteCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function findActiveCart(QuoteCollectionTransfer $quoteCollectionTransfer): QuoteTransfer
    {
        foreach ($quoteCollectionTransfer->getQuotes() as $quoteTransfer) {
            if ($quoteTransfer->getIsActive()) {
                return $quoteTransfer;
            }
        }

        return null;
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
        return '@MultiCartWidget/_shop-layout/mini-cart.twig';
    }
}
