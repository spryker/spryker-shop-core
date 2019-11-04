<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ConfigurableBundleCartNoteWidget\Controller;

use Spryker\Yves\Kernel\Controller\AbstractController;
use SprykerShop\Yves\ConfigurableBundleCartNoteWidget\Form\ConfigurableBundleCartNoteForm;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \SprykerShop\Yves\ConfigurableBundleCartNoteWidget\ConfigurableBundleCartNoteWidgetFactory getFactory()
 */
class CartNoteController extends AbstractController
{
    protected const MESSAGE_CONFIGURABLE_BUNDLE_CART_NOTE_ADDED_SUCCESS = 'configurable_bundle_cart_note.note_added';
    protected const REQUEST_HEADER_REFERER = 'referer';

    /**
     * @uses \SprykerShop\Yves\CartPage\Plugin\Router\CartPageRouteProviderPlugin::ROUTE_CART
     */
    protected const ROUTE_CART = 'cart';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function addAction(Request $request): RedirectResponse
    {
        $this->handleConfigurableBundleCartNoteForm($request);

        return $this->getRedirectResponse($request);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return void
     */
    protected function handleConfigurableBundleCartNoteForm(Request $request): void
    {
        $form = $this->getFactory()
            ->getConfigurableBundleCartNoteForm()
            ->handleRequest($request);

        if (!$form->isSubmitted() || !$form->isValid()) {
            return;
        }

        $note = (string)$form->get(ConfigurableBundleCartNoteForm::FIELD_CONFIGURABLE_BUNDLE_CART_NOTE)->getData();
        $groupKey = (string)$form->get(ConfigurableBundleCartNoteForm::FIELD_CONFIGURABLE_BUNDLE_GROUP_KEY)->getData();

        $quoteResponseTransfer = $this->getFactory()
            ->getConfigurableBundleCartNoteClient()
            ->setCartNoteToConfigurableBundle($note, $groupKey);

        if ($quoteResponseTransfer->getIsSuccessful()) {
            $this->addSuccessMessage(
                $this->getSuccessMessage($groupKey)
            );
        }
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    protected function getRedirectResponse(Request $request): RedirectResponse
    {
        if ($request->headers->has(static::REQUEST_HEADER_REFERER)) {
            $refererUrl = (string)$request->headers->get(static::REQUEST_HEADER_REFERER);

            return $this->redirectResponseExternal($refererUrl);
        }

        return $this->redirectResponseInternal(static::ROUTE_CART);
    }

    /**
     * @param string $templateName
     *
     * @return string
     */
    protected function getSuccessMessage(string $templateName): string
    {
        return $this->getFactory()
            ->getGlossaryStorageClient()
            ->translate(static::MESSAGE_CONFIGURABLE_BUNDLE_CART_NOTE_ADDED_SUCCESS, $this->getLocale(), ['%template-name%' => $templateName]);
    }
}
