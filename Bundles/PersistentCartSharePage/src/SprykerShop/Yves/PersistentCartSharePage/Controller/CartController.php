<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\PersistentCartSharePage\Controller;

use SprykerShop\Yves\ShopApplication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \SprykerShop\Yves\PersistentCartSharePage\PersistentCartSharePageFactory getFactory()
 */
class CartController extends AbstractController
{
    /**
     * @param string $resourceShareUuid
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Spryker\Yves\Kernel\View\View|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function previewAction(string $resourceShareUuid, Request $request)
    {
        $response = $this->executePreviewAction($resourceShareUuid, $request);

        if (!is_array($response)) {
            return $response;
        }

        return $this->view($response, [], '@PersistentCartSharePage/views/cart-preview/cart-preview.twig');
    }

    /**
     * @param string $resourceShareUuid
     * @param \Symfony\Component\HttpFoundation\Request $request
     */
    protected function executePreviewAction(string $resourceShareUuid, Request $request)
    {
        $quoteResponceTransfer = $this->getFactory()
            ->getPersistentCartShareClient()
            ->getQuoteForPreview($resourceShareUuid);

        $quoteTransfer = $quoteResponceTransfer->getQuoteTransfer();
        $cartItems = $quoteTransfer->getItems();

        return [
            'cart' => $quoteTransfer,
            'isQuoteEditable' => false,
            'cartItems' => $cartItems,
            'attributes' => [],
            'isQuoteValid' => false,
        ];
    }
}
