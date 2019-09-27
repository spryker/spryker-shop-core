<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ConfigurableBundleWidget\Controller;

use Generated\Shared\Transfer\QuoteResponseTransfer;
use Generated\Shared\Transfer\UpdateConfiguredBundleRequestTransfer;
use Spryker\Yves\Kernel\PermissionAwareTrait;
use SprykerShop\Yves\ShopApplication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @method \SprykerShop\Yves\ConfigurableBundleWidget\ConfigurableBundleWidgetFactory getFactory()
 */
class CartController extends AbstractController
{
    use PermissionAwareTrait;

    protected const GLOSSARY_KEY_PERMISSION_FAILED = 'global.permission.failed';
    protected const GLOSSARY_KEY_CONFIGURED_BUNDLE_REMOVED = 'configured_bundle_widget.configured_bundle.removed';
    protected const GLOSSARY_KEY_CONFIGURED_BUNDLE_UPDATED = 'configured_bundle_widget.configured_bundle.updated';

    /**
     * @uses \SprykerShop\Shared\CartPage\Plugin\RemoveCartItemPermissionPlugin::KEY
     */
    protected const REMOVE_CART_ITEM_PERMISSION_PLUGIN_KEY = 'RemoveCartItemPermissionPlugin';

    /**
     * @uses \SprykerShop\Shared\CartPage\Plugin\ChangeCartItemPermissionPlugin::KEY
     */
    protected const CHANGE_CART_ITEM_PERMISSION_PLUGIN_KEY = 'ChangeCartItemPermissionPlugin';

    /**
     * @uses \SprykerShop\Yves\CartPage\Plugin\Provider\CartControllerProvider::ROUTE_CART
     */
    protected const ROUTE_CART = 'cart';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param string $groupKey
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function removeConfiguredBundleAction(Request $request, string $groupKey): Response
    {
        $response = $this->executeRemoveConfiguredBundleAction($request, $groupKey);

        return $response;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param string $groupKey
     * @param int $quantity
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function changeConfiguredBundleQuantityAction(Request $request, string $groupKey, int $quantity): Response
    {
        $response = $this->executeChangeConfiguredBundleQuantityAction($request, $groupKey, $quantity);

        return $response;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param string $groupKey
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function executeRemoveConfiguredBundleAction(Request $request, string $groupKey): Response
    {
        if (!$this->canRemoveCartItem()) {
            $this->addErrorMessage(static::GLOSSARY_KEY_PERMISSION_FAILED);

            return $this->redirectResponseInternal(static::ROUTE_CART);
        }

        $updateConfiguredBundleRequestTransfer = $this->createUpdateConfiguredBundleRequest($groupKey);

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
     * @param string $groupKey
     * @param int $quantity
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function executeChangeConfiguredBundleQuantityAction(Request $request, string $groupKey, int $quantity): Response
    {
        if (!$this->canChangeCartItem($quantity)) {
            $this->addErrorMessage(static::GLOSSARY_KEY_PERMISSION_FAILED);

            return $this->redirectResponseInternal(static::ROUTE_CART);
        }

        $updateConfiguredBundleRequestTransfer = $this->createUpdateConfiguredBundleRequest($groupKey, $quantity);

        $quoteResponseTransfer = $this->getFactory()
            ->getConfigurableBundleClient()
            ->updateConfiguredBundleQuantity($updateConfiguredBundleRequestTransfer);

        if ($quoteResponseTransfer->getIsSuccessful()) {
            $this->addSuccessMessage(static::GLOSSARY_KEY_CONFIGURED_BUNDLE_UPDATED);
        }

        $this->handleResponseErrors($quoteResponseTransfer);

        return $this->redirectResponseInternal(static::ROUTE_CART);
    }

    /**
     * @param string $groupKey
     * @param int|null $quantity
     *
     * @return \Generated\Shared\Transfer\UpdateConfiguredBundleRequestTransfer
     */
    protected function createUpdateConfiguredBundleRequest(string $groupKey, ?int $quantity = null): UpdateConfiguredBundleRequestTransfer
    {
        return (new UpdateConfiguredBundleRequestTransfer())
            ->setQuote($this->getFactory()->getQuoteClient()->getQuote())
            ->setGroupKey($groupKey)
            ->setQuantity($quantity);
    }

    /**
     * @param int|null $itemQuantity
     *
     * @return bool
     */
    protected function canChangeCartItem(?int $itemQuantity = null): bool
    {
        if ($itemQuantity === 0) {
            return $this->canRemoveCartItem();
        }

        return $this->canPerformCartItemAction(static::CHANGE_CART_ITEM_PERMISSION_PLUGIN_KEY);
    }

    /**
     * @return bool
     */
    protected function canRemoveCartItem(): bool
    {
        return $this->canPerformCartItemAction(static::REMOVE_CART_ITEM_PERMISSION_PLUGIN_KEY);
    }

    /**
     * @param string $permissionPluginKey
     *
     * @return bool
     */
    protected function canPerformCartItemAction(string $permissionPluginKey): bool
    {
        $quoteTransfer = $this->getFactory()
            ->getQuoteClient()
            ->getQuote();

        $isQuoteEditable = $this->getFactory()
            ->getQuoteClient()
            ->isQuoteEditable($quoteTransfer);

        return $isQuoteEditable && $this->can($permissionPluginKey);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteResponseTransfer $quoteResponseTransfer
     *
     * @return void
     */
    protected function handleResponseErrors(QuoteResponseTransfer $quoteResponseTransfer): void
    {
        foreach ($quoteResponseTransfer->getErrors() as $errorTransfer) {
            $this->addErrorMessage($errorTransfer->getMessage());
        }
    }
}
