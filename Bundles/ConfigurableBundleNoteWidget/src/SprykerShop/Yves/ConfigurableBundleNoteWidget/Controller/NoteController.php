<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ConfigurableBundleNoteWidget\Controller;

use Spryker\Yves\Kernel\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \SprykerShop\Yves\ConfigurableBundleNoteWidget\ConfigurableBundleNoteWidgetFactory getFactory()
 */
class NoteController extends AbstractController
{
    protected const GLOSSARY_KEY_CONFIGURABLE_BUNDLE_NOTE_ADDED = 'configurable_bundle_note.note_added';
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
        $redirectResponse = $this->executeAddAction($request);

        return $redirectResponse;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    protected function executeAddAction(Request $request): RedirectResponse
    {
        $configurableBundleNoteForm = $this->getFactory()
            ->getConfigurableBundleNoteForm()
            ->handleRequest($request);

        if (!$configurableBundleNoteForm->isSubmitted() || !$configurableBundleNoteForm->isValid()) {
            return $this->getRedirectResponse($request);
        }

        $quoteResponseTransfer = $this->getFactory()
            ->createConfigurableBundleNoteHandler()
            ->setConfiguredBundleNote($configurableBundleNoteForm->getData());

        if ($quoteResponseTransfer->getIsSuccessful()) {
            $this->addSuccessMessage(static::GLOSSARY_KEY_CONFIGURABLE_BUNDLE_NOTE_ADDED);
        }

        return $this->getRedirectResponse($request);
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
}
