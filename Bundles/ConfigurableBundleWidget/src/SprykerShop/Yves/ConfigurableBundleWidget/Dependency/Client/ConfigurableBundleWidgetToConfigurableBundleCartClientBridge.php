<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ConfigurableBundleWidget\Dependency\Client;

use Generated\Shared\Transfer\QuoteResponseTransfer;

class ConfigurableBundleWidgetToConfigurableBundleCartClientBridge implements ConfigurableBundleWidgetToConfigurableBundleCartClientInterface
{
    /**
     * @var \Spryker\Client\ConfigurableBundleCart\ConfigurableBundleCartClientInterface
     */
    protected $configurableBundleCartClient;

    /**
     * @param \Spryker\Client\ConfigurableBundleCart\ConfigurableBundleCartClientInterface $configurableBundleCartClient
     */
    public function __construct($configurableBundleCartClient)
    {
        $this->configurableBundleCartClient = $configurableBundleCartClient;
    }

    /**
     * @param string $configuredBundleGroupKey
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function removeConfiguredBundle(string $configuredBundleGroupKey): QuoteResponseTransfer
    {
        return $this->configurableBundleCartClient->removeConfiguredBundle($configuredBundleGroupKey);
    }

    /**
     * @param string $configuredBundleGroupKey
     * @param int $configuredBundleQuantity
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function updateConfiguredBundleQuantity(string $configuredBundleGroupKey, int $configuredBundleQuantity): QuoteResponseTransfer
    {
        return $this->configurableBundleCartClient->updateConfiguredBundleQuantity($configuredBundleGroupKey, $configuredBundleQuantity);
    }
}
