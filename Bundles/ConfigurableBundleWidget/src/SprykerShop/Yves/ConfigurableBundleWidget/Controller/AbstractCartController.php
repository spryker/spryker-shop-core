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

/**
 * @method \SprykerShop\Yves\ConfigurableBundleWidget\ConfigurableBundleWidgetFactory getFactory()
 */
abstract class AbstractCartController extends AbstractController
{
    use PermissionAwareTrait;

    /**
     * @var string
     */
    protected const GLOSSARY_KEY_PERMISSION_FAILED = 'global.permission.failed';

    /**
     * @var string
     */
    protected const GLOSSARY_KEY_CONFIGURED_BUNDLE_REMOVED = 'configured_bundle_widget.configured_bundle.removed';

    /**
     * @var string
     */
    protected const GLOSSARY_KEY_CONFIGURED_BUNDLE_UPDATED = 'configured_bundle_widget.configured_bundle.updated';

    /**
     * @var string
     */
    protected const MESSAGE_FORM_CSRF_VALIDATION_ERROR = 'form.csrf.error.text';

    /**
     * @uses \SprykerShop\Shared\CartPage\Plugin\RemoveCartItemPermissionPlugin::KEY
     *
     * @var string
     */
    protected const REMOVE_CART_ITEM_PERMISSION_PLUGIN_KEY = 'RemoveCartItemPermissionPlugin';

    /**
     * @uses \SprykerShop\Shared\CartPage\Plugin\ChangeCartItemPermissionPlugin::KEY
     *
     * @var string
     */
    protected const CHANGE_CART_ITEM_PERMISSION_PLUGIN_KEY = 'ChangeCartItemPermissionPlugin';

    /**
     * @param string $configuredBundleGroupKey
     * @param int|null $quantity
     *
     * @return \Generated\Shared\Transfer\UpdateConfiguredBundleRequestTransfer
     */
    protected function createUpdateConfiguredBundleRequest(string $configuredBundleGroupKey, ?int $quantity = null): UpdateConfiguredBundleRequestTransfer
    {
        return (new UpdateConfiguredBundleRequestTransfer())
            ->setQuote($this->getFactory()->getQuoteClient()->getQuote())
            ->setGroupKey($configuredBundleGroupKey)
            ->setQuantity($quantity);
    }

    /**
     * @param int|null $itemQuantity
     *
     * @return bool
     */
    protected function canChangeCartItem(?int $itemQuantity = null): bool
    {
        if (!$this->getFactory()->getModuleConfig()->isQuantityChangeable()) {
            return false;
        }

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
