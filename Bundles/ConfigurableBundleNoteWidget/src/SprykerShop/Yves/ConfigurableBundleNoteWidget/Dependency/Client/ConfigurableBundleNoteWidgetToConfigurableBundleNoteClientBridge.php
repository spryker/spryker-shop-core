<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ConfigurableBundleNoteWidget\Dependency\Client;

use Generated\Shared\Transfer\ConfiguredBundleNoteRequestTransfer;
use Generated\Shared\Transfer\QuoteResponseTransfer;

class ConfigurableBundleNoteWidgetToConfigurableBundleNoteClientBridge implements ConfigurableBundleNoteWidgetToConfigurableBundleNoteClientInterface
{
    /**
     * @var \Spryker\Client\ConfigurableBundleNote\ConfigurableBundleNoteClientInterface
     */
    protected $configurableBundleNoteClient;

    /**
     * @param \Spryker\Client\ConfigurableBundleNote\ConfigurableBundleNoteClientInterface $configurableBundleNoteClient
     */
    public function __construct($configurableBundleNoteClient)
    {
        $this->configurableBundleNoteClient = $configurableBundleNoteClient;
    }

    /**
     * @param \Generated\Shared\Transfer\ConfiguredBundleNoteRequestTransfer $configuredBundleNoteRequestTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function setConfiguredBundleNote(
        ConfiguredBundleNoteRequestTransfer $configuredBundleNoteRequestTransfer
    ): QuoteResponseTransfer {
        return $this->configurableBundleNoteClient->setConfiguredBundleNote($configuredBundleNoteRequestTransfer);
    }
}
