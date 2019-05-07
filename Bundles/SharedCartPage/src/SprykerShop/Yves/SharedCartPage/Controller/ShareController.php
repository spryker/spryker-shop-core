<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\SharedCartPage\Controller;

use Generated\Shared\Transfer\QuoteTransfer;
use SprykerShop\Yves\ShopApplication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @method \SprykerShop\Yves\SharedCartPage\SharedCartPageFactory getFactory()
 */
class ShareController extends AbstractController
{
    public const KEY_GLOSSARY_SHARED_CART_PAGE_SHARE_SUCCESS = 'shared_cart_page.share.success';
    public const URL_REDIRECT_MULTI_CART_PAGE = 'multi-cart';
    /**
     * @see \Spryker\Shared\SharedCart\SharedCartConfig::PERMISSION_GROUP_OWNER_ACCESS
     */
    protected const PERMISSION_GROUP_OWNER_ACCESS = 'OWNER_ACCESS';

    /**
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     *
     * @return void
     */
    public function initialize()
    {
        parent::initialize();

        $customerTransfer = $this->getFactory()
            ->getCustomerClient()
            ->getCustomer();

        if ($customerTransfer === null || !$customerTransfer->getCompanyUserTransfer()) {
            throw new NotFoundHttpException('Only company users are allowed to access this page');
        }
    }

    /**
     * @param int $idQuote
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Spryker\Yves\Kernel\View\View|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function indexAction(int $idQuote, Request $request)
    {
        $response = $this->executeIndexAction($idQuote, $request);

        if (!is_array($response)) {
            return $response;
        }

        return $this->view($response, [], '@SharedCartPage/views/cart-share/cart-share.twig');
    }

    /**
     * @param int $idQuote
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    protected function executeIndexAction(int $idQuote, Request $request)
    {
        $quoteTransfer = $this->getFactory()
            ->getMultiCartClient()
            ->findQuoteById($idQuote);

        if (!$this->canShareQuote($quoteTransfer)) {
            return $this->redirectResponseInternal(static::URL_REDIRECT_MULTI_CART_PAGE);
        }

        $sharedCartForm = $this->getFactory()
            ->getShareCartForm($idQuote)
            ->handleRequest($request);

        if ($sharedCartForm->isSubmitted() && $sharedCartForm->isValid()) {
            $shareCartRequestTransfer = $sharedCartForm->getData();
            $quoteResponseTransfer = $this->getFactory()->getSharedCartClient()
                ->updateQuotePermissions($shareCartRequestTransfer);
            if ($quoteResponseTransfer->getIsSuccessful()) {
                $this->addSuccessMessage(static::KEY_GLOSSARY_SHARED_CART_PAGE_SHARE_SUCCESS);

                return $this->redirectResponseInternal(static::URL_REDIRECT_MULTI_CART_PAGE);
            }
        }

        return [
            'idQuote' => $idQuote,
            'sharedCartForm' => $sharedCartForm->createView(),
            'cart' => $quoteTransfer,
        ];
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer|null $quoteTransfer
     *
     * @return bool
     */
    protected function canShareQuote(?QuoteTransfer $quoteTransfer = null): bool
    {
        if (!$quoteTransfer || $this->isQuoteLocked($quoteTransfer)) {
            return false;
        }

        return $this->isQuoteAccessOwner($quoteTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    protected function isQuoteLocked(QuoteTransfer $quoteTransfer): bool
    {
        return $this->getFactory()
            ->getQuoteClient()
            ->isQuoteLocked($quoteTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    protected function isQuoteAccessOwner(QuoteTransfer $quoteTransfer): bool
    {
        return $this->getFactory()->getSharedCartClient()->getQuoteAccessLevel($quoteTransfer) === static::PERMISSION_GROUP_OWNER_ACCESS;
    }
}
