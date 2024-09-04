<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CartPage\Expander;

use Generated\Shared\Transfer\MiniCartViewTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use SprykerShop\Yves\CartPage\CartPageConfig;
use Twig\Environment;

class MiniCartViewExpander implements MiniCartViewExpanderInterface
{
    /**
     * @var \Twig\Environment
     */
    protected Environment $twigEnvironment;

    /**
     * @var \SprykerShop\Yves\CartPage\CartPageConfig
     */
    protected CartPageConfig $cartPageConfig;

    /**
     * @param \Twig\Environment $twigEnvironment
     * @param \SprykerShop\Yves\CartPage\CartPageConfig $cartPageConfig
     */
    public function __construct(Environment $twigEnvironment, CartPageConfig $cartPageConfig)
    {
        $this->twigEnvironment = $twigEnvironment;
        $this->cartPageConfig = $cartPageConfig;
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
        $cartBlockMiniCartViewTemplatePath = $this->cartPageConfig->getCartBlockMiniCartViewTemplatePath();
        if ($cartBlockMiniCartViewTemplatePath && !$miniCartViewTransfer->getCounterOnly()) {
            $content = $this->twigEnvironment->render(
                $cartBlockMiniCartViewTemplatePath,
                ['quote' => $quoteTransfer],
            );

            $miniCartViewTransfer->setContent($content);
        }

        return $miniCartViewTransfer;
    }
}
