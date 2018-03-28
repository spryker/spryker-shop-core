<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\SharedCartPage\Controller;

use SprykerShop\Yves\CartPage\Plugin\Provider\CartControllerProvider;
use SprykerShop\Yves\ShopApplication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \SprykerShop\Yves\SharedCartPage\SharedCartPageFactory getFactory()
 */
class ShareController extends AbstractController
{
    /**
     * @param int $idQuote
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Spryker\Yves\Kernel\View\View|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function indexAction(int $idQuote, Request $request)
    {
        $sharedCartForm = $this->getFactory()
            ->getShareCartForm($idQuote)
            ->handleRequest($request);

        if ($sharedCartForm->isSubmitted() && $sharedCartForm->isValid()) {
            $shareCartRequestTransfer = $sharedCartForm->getData();
            $quoteResponseTransfer = $this->getFactory()->getSharedCartClient()
                ->addShareCart($shareCartRequestTransfer);
            if ($quoteResponseTransfer->getIsSuccessful()) {
                $this->addSuccessMessage('shared_cart_page.share.success');
                return $this->redirectResponseInternal(CartControllerProvider::ROUTE_CART);
            }
        }

        $data = [
            'idQuote' => $idQuote,
            'sharedCartForm' => $sharedCartForm->createView(),
        ];

        return $this->view($data, [], '@SharedCartPage/views/cart-share/cart-share.twig');
    }
}
