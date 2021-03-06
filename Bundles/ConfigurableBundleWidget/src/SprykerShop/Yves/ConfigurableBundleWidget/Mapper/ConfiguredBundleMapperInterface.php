<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ConfigurableBundleWidget\Mapper;

use Generated\Shared\Transfer\ConfiguredBundleCollectionTransfer;
use Generated\Shared\Transfer\QuoteTransfer;

interface ConfiguredBundleMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\ConfiguredBundleCollectionTransfer
     */
    public function mapQuoteToConfiguredBundles(QuoteTransfer $quoteTransfer): ConfiguredBundleCollectionTransfer;
}
