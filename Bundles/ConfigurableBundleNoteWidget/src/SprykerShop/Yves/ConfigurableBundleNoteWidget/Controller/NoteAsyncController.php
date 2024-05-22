<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ConfigurableBundleNoteWidget\Controller;

use Spryker\Yves\Kernel\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \SprykerShop\Yves\ConfigurableBundleNoteWidget\ConfigurableBundleNoteWidgetFactory getFactory()
 */
class NoteAsyncController extends AbstractController
{
    /**
     * @var string
     */
    protected const KEY_MESSAGES = 'messages';

    /**
     * @var string
     */
    protected const FLASH_MESSAGE_LIST_TEMPLATE_PATH = '@ShopUi/components/organisms/flash-message-list/flash-message-list.twig';

    /**
     * @var string
     */
    protected const GLOSSARY_KEY_CONFIGURABLE_BUNDLE_NOTE_ADDED = 'configurable_bundle_note.note_added';

    /**
     * @var string
     */
    protected const MESSAGE_FORM_CSRF_VALIDATION_ERROR = 'form.csrf.error.text';

    /**
     * @uses \SprykerShop\Yves\CartPage\Plugin\Router\CartPageAsyncRouteProviderPlugin::ROUTE_NAME_CART_ASYNC_VIEW
     *
     * @var string
     */
    protected const ROUTE_NAME_CART_ASYNC_VIEW = 'cart/async/view';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\JsonResponse
     */
    public function addAction(Request $request)
    {
        $configurableBundleNoteForm = $this->getFactory()
            ->getConfigurableBundleNoteForm()
            ->handleRequest($request);

        if (!$configurableBundleNoteForm->isSubmitted() || !$configurableBundleNoteForm->isValid()) {
            $this->addErrorMessage(static::MESSAGE_FORM_CSRF_VALIDATION_ERROR);

            return $this->getMessagesJsonResponse();
        }

        $quoteResponseTransfer = $this->getFactory()
            ->createConfigurableBundleNoteHandler()
            ->setConfiguredBundleNote($configurableBundleNoteForm->getData());

        if ($quoteResponseTransfer->getIsSuccessful()) {
            $this->addSuccessMessage(static::GLOSSARY_KEY_CONFIGURABLE_BUNDLE_NOTE_ADDED);
        }

        return $this->redirectResponseInternal(static::ROUTE_NAME_CART_ASYNC_VIEW);
    }

    /**
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    protected function getMessagesJsonResponse(): JsonResponse
    {
        return $this->jsonResponse([
            static::KEY_MESSAGES => $this->renderView(static::FLASH_MESSAGE_LIST_TEMPLATE_PATH)->getContent(),
        ]);
    }
}
