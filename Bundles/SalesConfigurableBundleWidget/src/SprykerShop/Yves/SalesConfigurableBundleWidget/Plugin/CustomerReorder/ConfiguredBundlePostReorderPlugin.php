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
    /**
     * {@inheritDoc}
     * - Adds flash message if $itemTransfers has Configured Bundle.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\ItemTransfer[] $itemTransfers
     *
     * @return void
     */
    public function execute(QuoteTransfer $quoteTransfer, array $itemTransfers): void
    {
        $this->getFactory()
            ->createConfiguredBundleChecker()
            ->addConfigurableBundleFlashMessage($itemTransfers);
    }
}
