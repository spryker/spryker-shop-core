<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ConfigurableBundlePage\Dependency\Client;

use Generated\Shared\Transfer\CreateConfiguredBundleRequestTransfer;
use Generated\Shared\Transfer\QuoteResponseTransfer;

class ConfigurableBundlePageToConfigurableBundleCartClientBridge implements ConfigurableBundlePageToConfigurableBundleCartClientInterface
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
     * @param \Generated\Shared\Transfer\CreateConfiguredBundleRequestTransfer $createConfiguredBundleRequestTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function addConfiguredBundle(CreateConfiguredBundleRequestTransfer $createConfiguredBundleRequestTransfer): QuoteResponseTransfer
    {
        return $this->configurableBundleCartClient->addConfiguredBundle($createConfiguredBundleRequestTransfer);
    }
}
