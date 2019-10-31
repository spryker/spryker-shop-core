<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\SalesConfigurableBundleWidget\Model;

use Spryker\Yves\Messenger\FlashMessenger\FlashMessengerInterface;

class ConfiguredBundleChecker implements ConfiguredBundleCheckerInterface
{
    public const GLOSSARY_KEY_CONFIGURED_BUNDLE_ITEMS_ADDED_TO_CART_SUCCESS = 'sales_configured_bundle_widget.success.items_added_to_cart_as_individual_products';

    /**
     * @var \Spryker\Yves\Messenger\FlashMessenger\FlashMessengerInterface $flashMessenger
     */
    protected $flashMessenger;

    /**
     * @param \Spryker\Yves\Messenger\FlashMessenger\FlashMessengerInterface $flashMessenger
     */
    public function __construct(FlashMessengerInterface $flashMessenger)
    {
        $this->flashMessenger = $flashMessenger;
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

            $this->flashMessenger->addSuccessMessage(static::GLOSSARY_KEY_CONFIGURED_BUNDLE_ITEMS_ADDED_TO_CART_SUCCESS);

            return;
        }
    }
}
