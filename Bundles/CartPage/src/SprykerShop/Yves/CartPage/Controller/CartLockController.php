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
class CartLockController extends AbstractController
{
    protected const GLOSSARY_KEY_CART_PAGE_RESET_LOCK_SUCCESS = 'cart_page.quote.reset_lock.success';

    /**
     * @uses \SprykerShop\Yves\CartPage\Plugin\Provider\CartControllerProvider::ROUTE_CART
     */
    protected const ROUTE_CART = 'cart';

    /**
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function resetLockAction(): RedirectResponse
    {
        $response = $this->executeResetLockAction();

        return $response;
    }

    /**
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    protected function executeResetLockAction(): RedirectResponse
    {
        $quoteResponseTransfer = $this->getFactory()
            ->getCartClient()
            ->resetQuoteLock();

        if ($quoteResponseTransfer->getIsSuccessful()) {
            $this->addSuccessMessage(static::GLOSSARY_KEY_CART_PAGE_RESET_LOCK_SUCCESS);
        }

        return $this->redirectResponseInternal(static::ROUTE_CART);
    }
}
