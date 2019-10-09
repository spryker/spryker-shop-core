<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ConfigurableBundleWidget\Grouper;

use Generated\Shared\Transfer\ConfiguredBundleTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use SprykerShop\Yves\ConfigurableBundleWidget\Mapper\ConfiguredBundleMapperInterface;

class ConfiguredBundleGrouper implements ConfiguredBundleGrouperInterface
{
    /**
     * @var \SprykerShop\Yves\ConfigurableBundleWidget\Mapper\ConfiguredBundleMapperInterface
     */
    protected $configuredBundleMapper;

    /**
     * @param \SprykerShop\Yves\ConfigurableBundleWidget\Mapper\ConfiguredBundleMapperInterface $configuredBundleMapper
     */
    public function __construct(ConfiguredBundleMapperInterface $configuredBundleMapper)
    {
        $this->configuredBundleMapper = $configuredBundleMapper;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param iterable|\Generated\Shared\Transfer\ItemTransfer[] $itemTransfers
     *
     * @return \Generated\Shared\Transfer\ConfiguredBundleTransfer[]
     */
    public function getConfiguredBundles(QuoteTransfer $quoteTransfer, iterable $itemTransfers): array
    {
        $configuredBundleCollectionTransfer = $this->configuredBundleMapper->mapQuoteToConfiguredBundles($quoteTransfer);

        if (count($quoteTransfer->getItems()) === count($itemTransfers)) {
            return $configuredBundleCollectionTransfer->getConfiguredBundles()->getArrayCopy();
        }

        $configuredBundleTransfers = [];

        foreach ($configuredBundleCollectionTransfer->getConfiguredBundles() as $configuredBundleTransfer) {
            if ($this->hasItems($configuredBundleTransfer, $itemTransfers)) {
                $configuredBundleTransfers[] = $configuredBundleTransfer;
            }
        }

        return $configuredBundleTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\ConfiguredBundleTransfer $configuredBundleTransfer
     * @param iterable|\Generated\Shared\Transfer\ItemTransfer[] $itemTransfers
     *
     * @return bool
     */
    protected function hasItems(ConfiguredBundleTransfer $configuredBundleTransfer, iterable $itemTransfers): bool
    {
        foreach ($itemTransfers as $itemTransfer) {
            if (in_array($itemTransfer->getGroupKey(), $this->getItemGroupKeysFromConfiguredBundle($configuredBundleTransfer))) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param \Generated\Shared\Transfer\ConfiguredBundleTransfer $configuredBundleTransfer
     *
     * @return string[]
     */
    protected function getItemGroupKeysFromConfiguredBundle(ConfiguredBundleTransfer $configuredBundleTransfer): array
    {
        $itemGroupKeys = [];

        foreach ($configuredBundleTransfer->getItems() as $itemTransfer) {
            $itemGroupKeys[] = $itemTransfer->getGroupKey();
        }

        return $itemGroupKeys;
    }
}
