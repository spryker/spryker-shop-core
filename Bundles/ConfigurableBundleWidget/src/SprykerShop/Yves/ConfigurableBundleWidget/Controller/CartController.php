<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ConfigurableBundleWidget\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @method \SprykerShop\Yves\ConfigurableBundleWidget\ConfigurableBundleWidgetFactory getFactory()
 */
class CartController extends AbstractCartController
{
    /**
     * @uses \SprykerShop\Yves\CartPage\Plugin\Router\CartPageRouteProviderPlugin::ROUTE_NAME_CART
     *
     * @var string
     */
    protected const ROUTE_CART = 'cart';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param string $configuredBundleGroupKey
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function removeConfiguredBundleAction(Request $request, string $configuredBundleGroupKey): Response
    {
        $response = $this->executeRemoveConfiguredBundleAction($request, $configuredBundleGroupKey);

        return $response;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param string $configuredBundleGroupKey
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function changeConfiguredBundleQuantityAction(Request $request, string $configuredBundleGroupKey): Response
    {
        $response = $this->executeChangeConfiguredBundleQuantityAction($request, $configuredBundleGroupKey);

        return $response;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param string $configuredBundleGroupKey
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function executeRemoveConfiguredBundleAction(Request $request, string $configuredBundleGroupKey): Response
    {
        if (!$this->canRemoveCartItem()) {
            $this->addErrorMessage(static::GLOSSARY_KEY_PERMISSION_FAILED);

            return $this->redirectResponseInternal(static::ROUTE_CART);
        }

        $form = $this->getFactory()->getConfiguredBundleRemoveItemForm()->handleRequest($request);

        if (!$form->isSubmitted() || !$form->isValid()) {
            $this->addErrorMessage(static::MESSAGE_FORM_CSRF_VALIDATION_ERROR);

            return $this->redirectResponseInternal(static::ROUTE_CART);
        }

        $updateConfiguredBundleRequestTransfer = $this->createUpdateConfiguredBundleRequest($configuredBundleGroupKey);

        $quoteResponseTransfer = $this->getFactory()
            ->getConfigurableBundleClient()
            ->removeConfiguredBundle($updateConfiguredBundleRequestTransfer);

        if ($quoteResponseTransfer->getIsSuccessful()) {
            $this->addSuccessMessage(static::GLOSSARY_KEY_CONFIGURED_BUNDLE_REMOVED);
        }

        $this->handleResponseErrors($quoteResponseTransfer);

        return $this->redirectResponseInternal(static::ROUTE_CART);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param string $configuredBundleGroupKey
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function executeChangeConfiguredBundleQuantityAction(Request $request, string $configuredBundleGroupKey): Response
    {
        $quantity = $request->get('quantity', 1);

        if (!$this->canChangeCartItem($quantity)) {
            $this->addErrorMessage(static::GLOSSARY_KEY_PERMISSION_FAILED);

            return $this->redirectResponseInternal(static::ROUTE_CART);
        }

        $form = $this->getFactory()->getChangeConfiguredBundleQuantityForm()->handleRequest($request);

        if (!$form->isSubmitted() || !$form->isValid()) {
            $this->addErrorMessage(static::MESSAGE_FORM_CSRF_VALIDATION_ERROR);

            return $this->redirectResponseInternal(static::ROUTE_CART);
        }

        $updateConfiguredBundleRequestTransfer = $this->createUpdateConfiguredBundleRequest($configuredBundleGroupKey, $quantity);

        $quoteResponseTransfer = $this->getFactory()
            ->getConfigurableBundleClient()
            ->updateConfiguredBundleQuantity($updateConfiguredBundleRequestTransfer);

        if ($quoteResponseTransfer->getIsSuccessful()) {
            $this->addSuccessMessage(static::GLOSSARY_KEY_CONFIGURED_BUNDLE_UPDATED);
        }

        $this->handleResponseErrors($quoteResponseTransfer);

        return $this->redirectResponseInternal(static::ROUTE_CART);
    }
}
