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
 * @deprecated Use {@link \Spryker\Zed\SalesConfigurableBundle\Communication\Plugin\CartReorder\ConfiguredBundleCartPostReorderPlugin} instead.
 *
 * @method \SprykerShop\Yves\SalesConfigurableBundleWidget\SalesConfigurableBundleWidgetFactory getFactory()
 */
class ConfiguredBundlePostReorderPlugin extends AbstractPlugin implements PostReorderPluginInterface
{
    /**
     * {@inheritDoc}
     * - Adds flash message if at least 1 of the provided "items" has configured bundle property.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param array<\Generated\Shared\Transfer\ItemTransfer> $itemTransfers
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
