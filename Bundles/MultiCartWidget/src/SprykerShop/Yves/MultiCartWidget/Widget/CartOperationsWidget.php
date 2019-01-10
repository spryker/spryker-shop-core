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
class CartOperationsWidget extends AbstractWidget
{
    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     */
    public function __construct(QuoteTransfer $quoteTransfer)
    {
        $this->addParameter('cart', $quoteTransfer)
            ->addParameter('isMultiCartAllowed', $this->isMultiCartAllowed())
            ->addParameter('isDeleteCartAllowed', $this->isDeleteCartAllowed());

        /** @deprecated Use global widgets instead. */
        $this->addWidgets($this->getFactory()->getViewExtendWidgetPlugins());
    }

    /**
     * @return string
     */
    public static function getName(): string
    {
        return 'CartOperationsWidget';
    }

    /**
     * @return string
     */
    public static function getTemplate(): string
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
