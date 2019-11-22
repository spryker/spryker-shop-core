<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ConfigurableBundleCartNoteWidget\Dependency\Client;

use Generated\Shared\Transfer\ConfiguredBundleCartNoteRequestTransfer;
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
     * @param \Generated\Shared\Transfer\ConfiguredBundleCartNoteRequestTransfer $configuredBundleCartNoteRequestTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function setConfiguredBundleCartNote(
        ConfiguredBundleCartNoteRequestTransfer $configuredBundleCartNoteRequestTransfer
    ): QuoteResponseTransfer {
        return $this->configurableBundleCartNoteClient->setConfiguredBundleCartNote($configuredBundleCartNoteRequestTransfer);
    }
}
