<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\SharedCartPage\Controller;

use Generated\Shared\Transfer\ShareCartRequestTransfer;
use SprykerShop\Yves\ShopApplication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;

/**
 * @method \SprykerShop\Yves\SharedCartPage\SharedCartPageFactory getFactory()
 */
class DismissController extends AbstractController
{
    /**
     * @uses \SprykerShop\Yves\MultiCartPage\Plugin\Provider\MultiCartPageControllerProvider::ROUTE_MULTI_CART_INDEX
     */
    public const URL_REDIRECT_MULTI_CART_PAGE = 'multi-cart';
    public const KEY_GLOSSARY_SHARED_CART_PAGE_DISMISS_SUCCESS = 'shared_cart_page.dismiss.success';

    /**
     * @param int $idQuote
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function indexAction(int $idQuote): RedirectResponse
    {
        $quoteTransfer = $this->getFactory()
            ->getMultiCartClient()
            ->findQuoteById($idQuote);

        if ($quoteTransfer === null) {
            return $this->redirectResponseInternal(static::URL_REDIRECT_MULTI_CART_PAGE);
        }

        $shareCartRequestTransfer = (new ShareCartRequestTransfer())
            ->setIdQuote($idQuote)
            ->setIdCompanyUser($this->getFactory()->getCustomerClient()->getCustomer()->getCompanyUserTransfer()->getIdCompanyUser());
        $quoteResponseTransfer = $this->getFactory()
            ->getSharedCartClient()
            ->removeShareCart($shareCartRequestTransfer);

        if ($quoteResponseTransfer->getIsSuccessful()) {
            $this->addSuccessMessage(static::KEY_GLOSSARY_SHARED_CART_PAGE_DISMISS_SUCCESS);
        }

        return $this->redirectResponseInternal(static::URL_REDIRECT_MULTI_CART_PAGE);
    }

    /**
     * @param int $idQuote
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Spryker\Yves\Kernel\View\View
     */
    public function confirmAction(int $idQuote)
    {
        $response = $this->executeConfirmAction($idQuote);

        if (!is_array($response)) {
            return $response;
        }

        return $this->view($response, [], '@SharedCartPage/views/dismiss-confirm/dismiss-confirm.twig');
    }

    /**
     * @param int $idQuote
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    protected function executeConfirmAction(int $idQuote)
    {
        $quoteTransfer = $this->getFactory()
            ->getMultiCartClient()
            ->findQuoteById($idQuote);

        if ($quoteTransfer === null) {
            return $this->redirectResponseInternal(static::URL_REDIRECT_MULTI_CART_PAGE);
        }

        return [
            'quote' => $quoteTransfer,
        ];
    }
}
