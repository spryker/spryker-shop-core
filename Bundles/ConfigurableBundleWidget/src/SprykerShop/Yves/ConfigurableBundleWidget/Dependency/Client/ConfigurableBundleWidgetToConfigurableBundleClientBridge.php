<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ConfigurableBundleWidget\Dependency\Client;

use Generated\Shared\Transfer\ConfiguredBundleCollectionTransfer;
use Generated\Shared\Transfer\QuoteTransfer;

class ConfigurableBundleWidgetToConfigurableBundleClientBridge implements ConfigurableBundleWidgetToConfigurableBundleClientInterface
{
    /**
     * @var \Spryker\Client\ConfigurableBundle\ConfigurableBundleClientInterface
     */
    protected $configurableBundleClient;

    /**
     * @param \Spryker\Client\ConfigurableBundle\ConfigurableBundleClientInterface $configurableBundleClient
     */
    public function __construct($configurableBundleClient)
    {
        $this->configurableBundleClient = $configurableBundleClient;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\ConfiguredBundleCollectionTransfer
     */
    public function getConfiguredBundlesFromQuote(QuoteTransfer $quoteTransfer): ConfiguredBundleCollectionTransfer
    {
        return $this->configurableBundleClient->getConfiguredBundlesFromQuote($quoteTransfer);
    }
}
