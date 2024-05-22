<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\MultiCartWidget\Expander;

use Generated\Shared\Transfer\MiniCartViewTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use SprykerShop\Yves\MultiCartWidget\DataProvider\MiniCartWidgetDataProviderInterface;
use Twig\Environment;

class MiniCartViewExpander implements MiniCartViewExpanderInterface
{
    /**
     * @var string
     */
    protected const MINI_CART_ASYNC_VIEW_TEMPLATE_PATH = '@MultiCartWidget/views/mini-cart-async/mini-cart-async.twig';

    /**
     * @var \SprykerShop\Yves\MultiCartWidget\DataProvider\MiniCartWidgetDataProviderInterface
     */
    protected MiniCartWidgetDataProviderInterface $miniCartWidgetDataProvider;

    /**
     * @var \Twig\Environment
     */
    protected Environment $twigEnvironment;

    /**
     * @param \SprykerShop\Yves\MultiCartWidget\DataProvider\MiniCartWidgetDataProviderInterface $miniCartWidgetDataProvider
     * @param \Twig\Environment $twigEnvironment
     */
    public function __construct(
        MiniCartWidgetDataProviderInterface $miniCartWidgetDataProvider,
        Environment $twigEnvironment
    ) {
        $this->miniCartWidgetDataProvider = $miniCartWidgetDataProvider;
        $this->twigEnvironment = $twigEnvironment;
    }

    /**
     * @param \Generated\Shared\Transfer\MiniCartViewTransfer $miniCartViewTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\MiniCartViewTransfer
     */
    public function expand(
        MiniCartViewTransfer $miniCartViewTransfer,
        QuoteTransfer $quoteTransfer
    ): MiniCartViewTransfer {
        $content = $this->twigEnvironment->render(static::MINI_CART_ASYNC_VIEW_TEMPLATE_PATH, [
            'cartQuantity' => $quoteTransfer->getTotalItemCount(),
            'activeCart' => $quoteTransfer,
            'cartList' => $this->miniCartWidgetDataProvider->getInActiveQuoteList(),
            'isMultiCartAllowed' => $this->miniCartWidgetDataProvider->isMultiCartAllowed(),
            'counterOnly' => $miniCartViewTransfer->getCounterOnly(),
        ]);

        $miniCartViewTransfer->setContent($content);

        return $miniCartViewTransfer;
    }
}
