<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\WishlistPage\Controller;

use Generated\Shared\Transfer\WishlistResponseTransfer;
use Generated\Shared\Transfer\WishlistTransfer;
use Spryker\Yves\Kernel\Controller\AbstractController;
use SprykerShop\Yves\WishlistPage\Plugin\Provider\WishlistPageControllerProvider;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \SprykerShop\Yves\WishlistPage\WishlistPageFactory getFactory()
 */
class WishlistOverviewController extends AbstractController
{
    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Spryker\Yves\Kernel\View\View|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function indexAction(Request $request)
    {
        $wishlistForm = $this->getFactory()
            ->getWishlistForm()
            ->handleRequest($request);

        if ($wishlistForm->isSubmitted() && $wishlistForm->isValid()) {
            $wishlistResponseTransfer = $this->getFactory()
                ->getWishlistClient()
                ->validateAndCreateWishlist($this->getWishlistTransfer($wishlistForm));

            if ($wishlistResponseTransfer->getIsSuccess()) {
                $this->addSuccessMessage('customer.account.wishlist.created');

                return $this->redirectResponseInternal(WishlistPageControllerProvider::ROUTE_WISHLIST_OVERVIEW);
            }

            $this->handleResponseErrors($wishlistResponseTransfer, $wishlistForm);
        }

        $wishlistCollection = $this->getFactory()
            ->getWishlistClient()
            ->getCustomerWishlistCollection();

        return $this->view([
            'wishlistCollection' => $wishlistCollection,
            'wishlistForm' => $wishlistForm->createView(),
        ], [], '@WishlistPage/views/wishlist-overview/wishlist-overview.twig');
    }

    /**
     * @param string $wishlistName
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Spryker\Yves\Kernel\View\View|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function updateAction($wishlistName, Request $request)
    {
        $wishlistFormDataProvider = $this->getFactory()->createWishlistFormDataProvider();
        $wishlistForm = $this->getFactory()
            ->getWishlistForm($wishlistFormDataProvider->getData($wishlistName))
            ->handleRequest($request);

        if ($wishlistForm->isSubmitted() && $wishlistForm->isValid()) {
            $wishlistResponseTransfer = $this->getFactory()
                ->getWishlistClient()
                ->validateAndUpdateWishlist($this->getWishlistTransfer($wishlistForm));

            if ($wishlistResponseTransfer->getIsSuccess()) {
                $this->addSuccessMessage('customer.account.wishlist.updated');

                return $this->redirectResponseInternal(WishlistPageControllerProvider::ROUTE_WISHLIST_OVERVIEW);
            }

            $this->handleResponseErrors($wishlistResponseTransfer, $wishlistForm);
        }
        $wishlistCollection = $this->getFactory()
            ->getWishlistClient()
            ->getCustomerWishlistCollection();

        return $this->view([
            'wishlistCollection' => $wishlistCollection,
            'wishlistForm' => $wishlistForm->createView(),
        ], [], '@WishlistPage/views/wishlist-update/wishlist-update.twig');
    }

    /**
     * @param string $wishlistName
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAction($wishlistName)
    {
        $wishlistTransfer = new WishlistTransfer();
        $wishlistTransfer
            ->setFkCustomer($this->getIdCustomer())
            ->setName($wishlistName);

        $this->getFactory()
            ->getWishlistClient()
            ->removeWishlistByName($wishlistTransfer);
        $this->addSuccessMessage('customer.account.wishlist.deleted');

        return $this->redirectResponseInternal(WishlistPageControllerProvider::ROUTE_WISHLIST_OVERVIEW);
    }

    /**
     * @param \Symfony\Component\Form\FormInterface $wishlistForm
     *
     * @return \Generated\Shared\Transfer\WishlistTransfer
     */
    protected function getWishlistTransfer(FormInterface $wishlistForm)
    {
        $wishlistTransfer = $wishlistForm->getData();
        $wishlistTransfer->setFkCustomer($this->getIdCustomer());

        return $wishlistTransfer;
    }

    /**
     * @return int
     */
    protected function getIdCustomer()
    {
        return $this->getFactory()
            ->getCustomerClient()
            ->getCustomer()
            ->getIdCustomer();
    }

    /**
     * @param \Generated\Shared\Transfer\WishlistResponseTransfer $wishlistResponseTransfer
     * @param \Symfony\Component\Form\FormInterface $wishlistForm
     *
     * @return void
     */
    protected function handleResponseErrors(WishlistResponseTransfer $wishlistResponseTransfer, FormInterface $wishlistForm)
    {
        foreach ($wishlistResponseTransfer->getErrors() as $error) {
            $wishlistForm->addError(new FormError($error));
        }
    }
}
