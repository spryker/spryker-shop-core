<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ConfigurableBundleNoteWidget\Handler;

use Generated\Shared\Transfer\ConfiguredBundleTransfer;
use Generated\Shared\Transfer\QuoteResponseTransfer;

interface ConfigurableBundleNoteHandlerInterface
{
    /**
     * @param \Generated\Shared\Transfer\ConfiguredBundleTransfer $configuredBundleTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function setConfiguredBundleNote(ConfiguredBundleTransfer $configuredBundleTransfer): QuoteResponseTransfer;
}
