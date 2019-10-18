<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ConfigurableBundleWidget\Dependency\Client;

use Generated\Shared\Transfer\QuoteResponseTransfer;
use Generated\Shared\Transfer\UpdateConfiguredBundleRequestTransfer;

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
     * @param \Generated\Shared\Transfer\UpdateConfiguredBundleRequestTransfer $updateConfiguredBundleRequestTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function removeConfiguredBundle(UpdateConfiguredBundleRequestTransfer $updateConfiguredBundleRequestTransfer): QuoteResponseTransfer
    {
        return $this->configurableBundleCartClient->removeConfiguredBundle($updateConfiguredBundleRequestTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\UpdateConfiguredBundleRequestTransfer $updateConfiguredBundleRequestTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function updateConfiguredBundleQuantity(UpdateConfiguredBundleRequestTransfer $updateConfiguredBundleRequestTransfer): QuoteResponseTransfer
    {
        return $this->configurableBundleCartClient->updateConfiguredBundleQuantity($updateConfiguredBundleRequestTransfer);
    }
}
