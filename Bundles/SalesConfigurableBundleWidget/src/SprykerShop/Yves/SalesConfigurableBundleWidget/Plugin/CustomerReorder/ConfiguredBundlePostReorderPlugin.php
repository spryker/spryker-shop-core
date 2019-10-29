<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\SalesConfigurableBundleWidget\Plugin\CustomerReorder;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Yves\Kernel\AbstractPlugin;
use SprykerShop\Yves\CustomerReorderWidgetExtension\Dependency\Plugin\PostReorderPluginInterface;

/**
 * @method \SprykerShop\Yves\SalesConfigurableBundleWidget\SalesConfigurableBundleWidgetFactory getFactory()
 */
class ConfiguredBundlePostReorderPlugin extends AbstractPlugin implements PostReorderPluginInterface
{
    public const GLOSSARY_KEY_CONFIGURED_BUNDLE_ITEMS_ADDED_TO_CART_SUCCESS = 'sales_configured_bundle_widget.success.items_added_to_cart_as_individual_products';

    /**
     * {@inheritDoc}
     * - Adds flash message if $itemsCollection has Configured Bundle.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\ItemTransfer[] $itemsCollection
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function execute(QuoteTransfer $quoteTransfer, array $itemsCollection): QuoteTransfer
    {
        if (!$itemsCollection) {
            return $quoteTransfer;
        }

        foreach ($itemsCollection as $itemTransfer) {
            if (!$itemTransfer->getSalesOrderConfiguredBundleItem()) {
                continue;
            }

            $this->getMessenger()->addSuccessMessage(static::GLOSSARY_KEY_CONFIGURED_BUNDLE_ITEMS_ADDED_TO_CART_SUCCESS);

            return $quoteTransfer;
        }

        return $quoteTransfer;
    }

    /**
     * @return \Spryker\Yves\Messenger\FlashMessenger\FlashMessengerInterface
     */
    protected function getMessenger()
    {
        return $this->getFactory()->getMessenger();
    }
}
