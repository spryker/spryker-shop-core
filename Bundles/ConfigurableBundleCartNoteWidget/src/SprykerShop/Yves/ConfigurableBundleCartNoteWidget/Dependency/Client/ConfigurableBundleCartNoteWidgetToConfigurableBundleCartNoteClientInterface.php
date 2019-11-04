<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ConfigurableBundleCartNoteWidget\Dependency\Client;

use Generated\Shared\Transfer\QuoteResponseTransfer;

interface ConfigurableBundleCartNoteWidgetToConfigurableBundleCartNoteClientInterface
{
    /**
     * @param string $cartNote
     * @param string $configurableBundleGroupKey
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function setCartNoteToConfigurableBundle(string $cartNote, string $configurableBundleGroupKey): QuoteResponseTransfer;
}
