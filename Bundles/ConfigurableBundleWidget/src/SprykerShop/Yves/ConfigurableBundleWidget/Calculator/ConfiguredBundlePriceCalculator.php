<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ConfigurableBundleWidget\Calculator;

use Generated\Shared\Transfer\ConfiguredBundlePriceTransfer;
use Generated\Shared\Transfer\ConfiguredBundleTransfer;
use Generated\Shared\Transfer\ItemTransfer;

class ConfiguredBundlePriceCalculator implements ConfiguredBundlePriceCalculatorInterface
{
    /**
     * @param \Generated\Shared\Transfer\ConfiguredBundleTransfer $configuredBundleTransfer
     *
     * @return \Generated\Shared\Transfer\ConfiguredBundlePriceTransfer
     */
    public function calculateConfiguredBundlePrice(ConfiguredBundleTransfer $configuredBundleTransfer): ConfiguredBundlePriceTransfer
    {
        $configuredBundlePriceTransfer = new ConfiguredBundlePriceTransfer();

        foreach ($configuredBundleTransfer->getItems() as $itemTransfer) {
            $configuredBundlePriceTransfer = $this->addItemPriceToConfigurableBundlePrice($configuredBundlePriceTransfer, $itemTransfer);
        }

        return $configuredBundlePriceTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ConfiguredBundlePriceTransfer $configuredBundlePriceTransfer
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return \Generated\Shared\Transfer\ConfiguredBundlePriceTransfer
     */
    protected function addItemPriceToConfigurableBundlePrice(
        ConfiguredBundlePriceTransfer $configuredBundlePriceTransfer,
        ItemTransfer $itemTransfer
    ): ConfiguredBundlePriceTransfer {

        $configuredBundlePriceTransfer->setSumSubtotalAggregation(
            $configuredBundlePriceTransfer->getSumSubtotalAggregation() + $itemTransfer->getSumSubtotalAggregation()
        );

        return $configuredBundlePriceTransfer;
    }
}
