<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\SharedCartPage\Controller;

use Generated\Shared\Transfer\ShareCartRequestTransfer;
use SprykerShop\Yves\CartPage\Plugin\Provider\CartControllerProvider;
use SprykerShop\Yves\ShopApplication\Controller\AbstractController;

/**
 * @method \SprykerShop\Yves\SharedCartPage\SharedCartPageFactory getFactory()
 */
class UnShareController extends AbstractController
{
    public const KEY_GLOSSARY_SHARED_CART_PAGE_UNSHARE_SUCCESS = 'shared_cart_page.unshare.success';

    /**
     * @param int $idQuote
     * @param int $idCompanyUser
     * @param int $idPermissionGroup
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function indexAction(int $idQuote, int $idCompanyUser, int $idPermissionGroup)
    {
        $shareCartRequestTransfer = new ShareCartRequestTransfer();
        $shareCartRequestTransfer->setIdQuote($idQuote);
        $shareCartRequestTransfer->setIdCompanyUser($idCompanyUser);
        $shareCartRequestTransfer->setIdQuotePermissionGroup($idPermissionGroup);
        $quoteResponseTransfer = $this->getFactory()->getSharedCartClient()
                        ->removeShareCart($shareCartRequestTransfer);
        if ($quoteResponseTransfer->getIsSuccessful()) {
            $this->addSuccessMessage(static::KEY_GLOSSARY_SHARED_CART_PAGE_UNSHARE_SUCCESS);
            return $this->redirectResponseInternal(CartControllerProvider::ROUTE_CART);
        }

        return $this->redirectResponseInternal(CartControllerProvider::ROUTE_CART);
    }
}
