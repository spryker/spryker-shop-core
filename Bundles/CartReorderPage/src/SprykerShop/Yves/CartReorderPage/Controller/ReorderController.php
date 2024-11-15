<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CartReorderPage\Controller;

use Generated\Shared\Transfer\CartReorderResponseTransfer;
use SprykerShop\Yves\ShopApplication\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \SprykerShop\Yves\CartReorderPage\CartReorderPageConfig getConfig()
 * @method \SprykerShop\Yves\CartReorderPage\CartReorderPageFactory getFactory()
 */
class ReorderController extends AbstractController
{
    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param string $orderReference
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function reorderAction(Request $request, string $orderReference): RedirectResponse
    {
        $cartReorderForm = $this->getFactory()
            ->getCartReorderForm()
            ->handleRequest($request);

        if (!$cartReorderForm->isSubmitted() || !$cartReorderForm->isValid()) {
            $this->addErrorMessagesFromForm($cartReorderForm);

            return $this->redirectToFailureUrl();
        }

        $cartReorderResponseTransfer = $this->getFactory()
            ->createCartReorderHandler()
            ->reorder($orderReference, $request);

        $this->handleCartReorderResponseErrors($cartReorderResponseTransfer);

        if ($cartReorderResponseTransfer->getErrors()->count()) {
            return $this->redirectToFailureUrl();
        }

        return $this->redirectToSuccessfulUrl();
    }

    /**
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    protected function redirectToFailureUrl(): RedirectResponse
    {
        return $this->redirectResponseInternal(
            $this->getFactory()->getConfig()->getReorderFailureRedirectUrl(),
        );
    }

    /**
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    protected function redirectToSuccessfulUrl(): RedirectResponse
    {
        return $this->redirectResponseInternal(
            $this->getFactory()->getConfig()->getReorderSuccessfulRedirectUrl(),
        );
    }

    /**
     * @param \Symfony\Component\Form\FormInterface $form
     *
     * @return void
     */
    protected function addErrorMessagesFromForm(FormInterface $form): void
    {
        /** @var list<\Symfony\Component\Form\FormError> $errors */
        $errors = $form->getErrors(true);
        foreach ($errors as $error) {
            $this->addErrorMessage($error->getMessage());
        }
    }

    /**
     * @param \Generated\Shared\Transfer\CartReorderResponseTransfer $cartReorderResponseTransfer
     *
     * @return void
     */
    protected function handleCartReorderResponseErrors(CartReorderResponseTransfer $cartReorderResponseTransfer): void
    {
        foreach ($cartReorderResponseTransfer->getErrors() as $errorTransfer) {
            $this->addErrorMessage($errorTransfer->getMessageOrFail());
        }
    }
}
