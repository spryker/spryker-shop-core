<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ConfigurableBundleWidget\Controller;

use Generated\Shared\Transfer\QuoteTransfer;
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

    /**
     * @uses \SprykerShop\Shared\CartPage\Plugin\RemoveCartItemPermissionPlugin::KEY
     */
    protected const REMOVE_CART_ITEM_PERMISSION_PLUGIN_KEY = 'RemoveCartItemPermissionPlugin';

    /**
     * @uses \SprykerShop\Yves\CartPage\Plugin\Provider\CartControllerProvider::ROUTE_CART
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
    protected function executeRemoveConfiguredBundleAction(Request $request, string $configuredBundleGroupKey): Response
    {
        if (!$this->canRemoveCartItem()) {
            $this->addErrorMessage(static::GLOSSARY_KEY_PERMISSION_FAILED);

            return $this->redirectResponseInternal(static::ROUTE_CART);
        }

        $itemTransfers = $this->getFactory()
            ->createQuoteReader()
            ->getItemsByConfiguredBundleGroupKey($configuredBundleGroupKey);

        $this->getFactory()
            ->getCartClient()
            ->removeItems($itemTransfers);

        $this->getFactory()
            ->getZedRequestClient()
            ->addResponseMessagesToMessenger();

        return $this->redirectResponseInternal(static::ROUTE_CART);
    }

    /**
     * @return bool
     */
    protected function canRemoveCartItem(): bool
    {
        $quoteTransfer = $this->getFactory()
            ->getCartClient()
            ->getQuote();

        return $this->isQuoteEditable($quoteTransfer) && $this->can(static::REMOVE_CART_ITEM_PERMISSION_PLUGIN_KEY);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    protected function isQuoteEditable(QuoteTransfer $quoteTransfer): bool
    {
        return $this->getFactory()
            ->getQuoteClient()
            ->isQuoteEditable($quoteTransfer);
    }
}
