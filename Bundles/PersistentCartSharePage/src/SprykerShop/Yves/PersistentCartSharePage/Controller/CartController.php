<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\PersistentCartSharePage\Controller;

use Generated\Shared\Transfer\ResourceShareRequestTransfer;
use Generated\Shared\Transfer\ResourceShareTransfer;
use Spryker\Yves\Kernel\View\View;
use SprykerShop\Yves\ShopApplication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @method \SprykerShop\Yves\PersistentCartSharePage\PersistentCartSharePageFactory getFactory()
 */
class CartController extends AbstractController
{
    /**
     * @param string $resourceShareUuid
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Spryker\Yves\Kernel\View\View
     */
    public function previewAction(string $resourceShareUuid, Request $request): View
    {
        $response = $this->executePreviewAction($resourceShareUuid, $request);

        return $this->view($response, [], '@PersistentCartSharePage/views/cart-preview/cart-preview.twig');
    }

    /**
     * @param string $resourceShareUuid
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     *
     * @return array
     */
    protected function executePreviewAction(string $resourceShareUuid, Request $request): array
    {
        $quoteResponseTransfer = $this->getFactory()
            ->getPersistentCartShareClient()
            ->getPreviewQuoteResourceShare(
                (new ResourceShareRequestTransfer())
                    ->setResourceShare(
                        (new ResourceShareTransfer())
                            ->setUuid($resourceShareUuid)
                    )
            );

        if (!$quoteResponseTransfer->getIsSuccessful()) {
            foreach ($quoteResponseTransfer->getErrors() as $quoteErrorTransfer) {
                $this->addErrorMessage($quoteErrorTransfer->getMessage());
            }

            throw new NotFoundHttpException();
        }

        $quoteTransfer = $quoteResponseTransfer->getQuoteTransfer();
        $cartItems = $quoteTransfer->getItems() ?? [];

        return [
            'cart' => $quoteTransfer,
            'isQuoteEditable' => false,
            'cartItems' => $cartItems,
            'attributes' => [],
            'isQuoteValid' => false,
            'isQuoteLocked' => false,
        ];
    }
}
