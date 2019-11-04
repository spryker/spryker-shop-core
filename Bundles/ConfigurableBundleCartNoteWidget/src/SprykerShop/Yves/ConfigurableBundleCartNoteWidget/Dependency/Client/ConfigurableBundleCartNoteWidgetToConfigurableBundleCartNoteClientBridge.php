<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ConfigurableBundleCartNoteWidget\Dependency\Client;

use Generated\Shared\Transfer\QuoteResponseTransfer;

class ConfigurableBundleCartNoteWidgetToConfigurableBundleCartNoteClientBridge implements ConfigurableBundleCartNoteWidgetToConfigurableBundleCartNoteClientInterface
{
    /**
     * @var \Spryker\Client\ConfigurableBundleCartNote\ConfigurableBundleCartNoteClientInterface
     */
    protected $configurableBundleCartNoteClient;

    /**
     * @param \Spryker\Client\ConfigurableBundleCartNote\ConfigurableBundleCartNoteClientInterface $configurableBundleCartNoteClient
     */
    public function __construct($configurableBundleCartNoteClient)
    {
        $this->configurableBundleCartNoteClient = $configurableBundleCartNoteClient;
    }

    /**
     * @param string $cartNote
     * @param string $configurableBundleGroupKey
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function setCartNoteToConfigurableBundle(string $cartNote, string $configurableBundleGroupKey): QuoteResponseTransfer
    {
        return $this->configurableBundleCartNoteClient->setCartNoteToConfigurableBundle($cartNote, $configurableBundleGroupKey);
    }
}
