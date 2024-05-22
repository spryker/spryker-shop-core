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
class MiniCartWidget extends AbstractWidget
{
    /**
     * @param int $cartQuantity
     */
    public function __construct($cartQuantity)
    {
        $this->addParameter('cartQuantity', $cartQuantity)
            ->addParameter('activeCart', $this->getActiveCart())
            ->addParameter('cartList', $this->getInActiveQuoteList())
            ->addParameter('isMultiCartAllowed', $this->isMultiCartAllowed());

        /** @deprecated Use global widgets instead. */
        $this->addWidgets($this->getFactory()->getViewExtendWidgetPlugins());
    }

    /**
     * @return string
     */
    public static function getName(): string
    {
        return 'MiniCartWidget';
    }

    /**
     * @return string
     */
    public static function getTemplate(): string
    {
        return '@MultiCartWidget/views/mini-cart/mini-cart.twig';
    }

    /**
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function getActiveCart(): QuoteTransfer
    {
        return $this->getFactory()->createMiniCartWidgetDataProvider()->getActiveCart();
    }

    /**
     * @return array<\Generated\Shared\Transfer\QuoteTransfer>
     */
    protected function getInActiveQuoteList(): array
    {
        return $this->getFactory()->createMiniCartWidgetDataProvider()->getInActiveQuoteList();
    }

    /**
     * @return bool
     */
    protected function isMultiCartAllowed(): bool
    {
        return $this->getFactory()->createMiniCartWidgetDataProvider()->isMultiCartAllowed();
    }
}
