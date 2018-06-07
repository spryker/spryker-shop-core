<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\MultiCartWidget\Plugin\CartPage;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Yves\Kernel\Widget\AbstractWidgetPlugin;
use SprykerShop\Yves\CartPage\Dependency\Plugin\MultiCartWidget\CartOperationsWidgetPluginInterface;

/**
 * @method \SprykerShop\Yves\MultiCartWidget\MultiCartWidgetFactory getFactory()
 */
class CartOperationsWidgetPlugin extends AbstractWidgetPlugin implements CartOperationsWidgetPluginInterface
{
    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    public function initialize(QuoteTransfer $quoteTransfer): void
    {
        $this
            ->addParameter('cart', $quoteTransfer)
            ->addParameter('isMultiCartAllowed', $this->isMultiCartAllowed())
            ->addParameter('isDeleteCartAllowed', $this->isDeleteCartAllowed())
            ->addWidgets($this->getFactory()->getViewExtendWidgetPlugins());
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
        return '@MultiCartWidget/views/cart-operations/cart-operations.twig';
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

    /**
     * @return bool
     */
    protected function isDeleteCartAllowed(): bool
    {
        $numberOfQuotes = count($this->getFactory()
            ->getMultiCartClient()
            ->getQuoteCollection()
            ->getQuotes());

        return $numberOfQuotes > 1;
    }
}
