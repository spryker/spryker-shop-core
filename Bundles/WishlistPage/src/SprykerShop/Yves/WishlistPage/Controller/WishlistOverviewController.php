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
        $response = $this->executeIndexAction($request);

        if (!is_array($response)) {
            return $response;
        }

        return $this->view($response, [], '@WishlistPage/views/wishlist-overview/wishlist-overview.twig');
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    protected function executeIndexAction(Request $request)
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

            if (!$wishlistResponseTransfer->getIsSuccess()) {
                foreach ($wishlistResponseTransfer->getErrors() as $error) {
                    $this->addErrorMessage($error);
                }

                return $this->redirectResponseInternal(WishlistPageControllerProvider::ROUTE_WISHLIST_OVERVIEW);
            }

            $this->handleResponseErrors($wishlistResponseTransfer, $wishlistForm);
        }

        $wishlistCollection = $this->getFactory()
            ->getWishlistClient()
            ->getCustomerWishlistCollection();

        return [
            'wishlistCollection' => $wishlistCollection,
            'wishlistForm' => $wishlistForm->createView(),
        ];
    }

    /**
     * @param string $wishlistName
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Spryker\Yves\Kernel\View\View|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function updateAction($wishlistName, Request $request)
    {
        $response = $this->executeUpdateAction($wishlistName, $request);

        if (!is_array($response)) {
            return $response;
        }

        return $this->view($response, [], '@WishlistPage/views/wishlist-overview-update/wishlist-overview-update.twig');
    }

    /**
     * @param string $wishlistName
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    protected function executeUpdateAction($wishlistName, Request $request)
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

        return [
            'wishlistCollection' => $wishlistCollection,
            'wishlistForm' => $wishlistForm->createView(),
        ];
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
