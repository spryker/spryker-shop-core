<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CartPage\Controller;

use SprykerShop\Yves\ShopApplication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;

/**
 * @method \SprykerShop\Yves\CartPage\CartPageFactory getFactory()
 */
class UnlockCartController extends AbstractController
{
    protected const GLOSSARY_KEY_CART_PAGE_UNLOCK_SUCCESS = 'cart_page.quote.unlock.success';

    /**
     * @uses \SprykerShop\Yves\CartPage\Plugin\Provider\CartControllerProvider::ROUTE_CART
     */
    protected const ROUTE_CART = 'cart';

    /**
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function indexAction(): RedirectResponse
    {
        $response = $this->executeIndexAction();

        return $response;
    }

    /**
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    protected function executeIndexAction(): RedirectResponse
    {
        $quoteResponseTransfer = $this->getFactory()
            ->getCartClient()
            ->unlockQuote();

        if ($quoteResponseTransfer->getIsSuccessful()) {
            $this->addSuccessMessage(static::GLOSSARY_KEY_CART_PAGE_UNLOCK_SUCCESS);
        }

        return $this->redirectResponseInternal(static::ROUTE_CART);
    }
}
