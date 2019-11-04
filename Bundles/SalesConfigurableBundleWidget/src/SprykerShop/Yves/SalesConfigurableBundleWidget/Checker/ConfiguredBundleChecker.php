<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\SalesConfigurableBundleWidget\Checker;

use SprykerShop\Yves\SalesConfigurableBundleWidget\Dependency\Client\SalesConfigurableBundleWidgetToMessengerClientBridgeInterface;

class ConfiguredBundleChecker implements ConfiguredBundleCheckerInterface
{
    public const GLOSSARY_KEY_CONFIGURED_BUNDLE_ITEMS_ADDED_TO_CART_SUCCESS = 'sales_configured_bundle_widget.success.items_added_to_cart_as_individual_products';

    /**
     * @var \SprykerShop\Yves\SalesConfigurableBundleWidget\Dependency\Client\SalesConfigurableBundleWidgetToMessengerClientBridgeInterface
     */
    protected $messengerClient;

    /**
     * @param \SprykerShop\Yves\SalesConfigurableBundleWidget\Dependency\Client\SalesConfigurableBundleWidgetToMessengerClientBridgeInterface $messengerClient
     */
    public function __construct(SalesConfigurableBundleWidgetToMessengerClientBridgeInterface $messengerClient)
    {
        $this->messengerClient = $messengerClient;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer[] $itemTransfers
     *
     * @return void
     */
    public function addConfigurableBundleFlashMessage(array $itemTransfers): void
    {
        foreach ($itemTransfers as $itemTransfer) {
            if (!$itemTransfer->getSalesOrderConfiguredBundleItem()) {
                continue;
            }

            $this->messengerClient->addSuccessMessage(static::GLOSSARY_KEY_CONFIGURED_BUNDLE_ITEMS_ADDED_TO_CART_SUCCESS);

            return;
        }
    }
}
