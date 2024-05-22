<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ConfigurableBundleWidget\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \SprykerShop\Yves\ConfigurableBundleWidget\ConfigurableBundleWidgetFactory getFactory()
 */
class CartAsyncController extends AbstractCartController
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
     * @uses \SprykerShop\Yves\CartPage\Plugin\Router\CartPageAsyncRouteProviderPlugin::ROUTE_NAME_CART_ASYNC_VIEW
     *
     * @var string
     */
    protected const ROUTE_NAME_CART_ASYNC_VIEW = 'cart/async/view';

    /**
     * @var string
     */
    protected const PARAM_QUANTITY = 'quantity';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param string $configuredBundleGroupKey
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\JsonResponse
     */
    public function removeConfiguredBundleAction(Request $request, string $configuredBundleGroupKey)
    {
        if (!$this->canRemoveCartItem()) {
            $this->addErrorMessage(static::GLOSSARY_KEY_PERMISSION_FAILED);

            return $this->getMessagesJsonResponse();
        }

        $form = $this->getFactory()->getConfiguredBundleRemoveItemForm()->handleRequest($request);

        if (!$form->isSubmitted() || !$form->isValid()) {
            $this->addErrorMessage(static::MESSAGE_FORM_CSRF_VALIDATION_ERROR);

            return $this->getMessagesJsonResponse();
        }

        $updateConfiguredBundleRequestTransfer = $this->createUpdateConfiguredBundleRequest($configuredBundleGroupKey);

        $quoteResponseTransfer = $this->getFactory()
            ->getConfigurableBundleClient()
            ->removeConfiguredBundle($updateConfiguredBundleRequestTransfer);

        if ($quoteResponseTransfer->getIsSuccessful()) {
            $this->addSuccessMessage(static::GLOSSARY_KEY_CONFIGURED_BUNDLE_REMOVED);
        }

        $this->handleResponseErrors($quoteResponseTransfer);

        return $this->redirectResponseInternal(static::ROUTE_NAME_CART_ASYNC_VIEW);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param string $configuredBundleGroupKey
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\JsonResponse
     */
    public function changeConfiguredBundleQuantityAction(Request $request, string $configuredBundleGroupKey)
    {
        $quantity = $request->get(static::PARAM_QUANTITY, 1);

        if (!$this->canChangeCartItem($quantity)) {
            $this->addErrorMessage(static::GLOSSARY_KEY_PERMISSION_FAILED);

            return $this->getMessagesJsonResponse();
        }

        $form = $this->getFactory()->getChangeConfiguredBundleQuantityForm()->handleRequest($request);

        if (!$form->isSubmitted() || !$form->isValid()) {
            $this->addErrorMessage(static::MESSAGE_FORM_CSRF_VALIDATION_ERROR);

            return $this->getMessagesJsonResponse();
        }

        $updateConfiguredBundleRequestTransfer = $this->createUpdateConfiguredBundleRequest($configuredBundleGroupKey, $quantity);

        $quoteResponseTransfer = $this->getFactory()
            ->getConfigurableBundleClient()
            ->updateConfiguredBundleQuantity($updateConfiguredBundleRequestTransfer);

        if ($quoteResponseTransfer->getIsSuccessful()) {
            $this->addSuccessMessage(static::GLOSSARY_KEY_CONFIGURED_BUNDLE_UPDATED);
        }

        $this->handleResponseErrors($quoteResponseTransfer);

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
