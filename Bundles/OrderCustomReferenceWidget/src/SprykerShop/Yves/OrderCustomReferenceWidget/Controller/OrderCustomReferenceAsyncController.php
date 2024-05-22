<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\OrderCustomReferenceWidget\Controller;

use SprykerShop\Yves\OrderCustomReferenceWidget\Form\OrderCustomReferenceForm;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \SprykerShop\Yves\OrderCustomReferenceWidget\OrderCustomReferenceWidgetFactory getFactory()
 */
class OrderCustomReferenceAsyncController extends AbstractOrderCustomReferenceController
{
    /**
     * @var string
     */
    protected const MESSAGE_FORM_CSRF_VALIDATION_ERROR = 'form.csrf.error.text';

    /**
     * @var string
     */
    protected const FLASH_MESSAGE_LIST_TEMPLATE_PATH = '@ShopUi/components/organisms/flash-message-list/flash-message-list.twig';

    /**
     * @var string
     */
    protected const KEY_MESSAGES = 'messages';

    /**
     * @var string
     */
    protected const KEY_CONTENT = 'content';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function saveAction(Request $request): JsonResponse
    {
        $orderCustomReferenceForm = $this->getFactory()
            ->getOrderCustomReferenceForm()
            ->handleRequest($request);

        if (!$orderCustomReferenceForm->isSubmitted() || !$orderCustomReferenceForm->isValid()) {
            $this->addErrorMessage(static::MESSAGE_FORM_CSRF_VALIDATION_ERROR);

            return $this->getMessagesJsonResponse();
        }

        $quoteClient = $this->getFactory()->getQuoteClient();

        $quoteResponseTransfer = $this->getFactory()
            ->getOrderCustomReferenceClient()
            ->setOrderCustomReference(
                $orderCustomReferenceForm->getData()[OrderCustomReferenceForm::FIELD_ORDER_CUSTOM_REFERENCE] ?? '',
                $quoteClient->getQuote(),
            );

        if ($quoteResponseTransfer->getIsSuccessful()) {
            $quoteClient->setQuote($quoteResponseTransfer->getQuoteTransfer());
            $this->addSuccessMessage(static::GLOSSARY_KEY_ORDER_CUSTOM_REFERENCE_SAVED);
        }

        $this->handleQuoteResponseTransferErrors($quoteResponseTransfer);

        return $this->jsonResponse([
            static::KEY_MESSAGES => $this->renderView(static::FLASH_MESSAGE_LIST_TEMPLATE_PATH)->getContent(),
            static::KEY_CONTENT => $this->getTwig()->render(
                '@OrderCustomReferenceWidget/views/order-custom-reference-async/order-custom-reference-async.twig',
                [
                    'quote' => $quoteResponseTransfer->getQuoteTransfer(),
                    'orderCustomReferenceForm' => $orderCustomReferenceForm->createView(),
                ],
            ),
        ]);
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
