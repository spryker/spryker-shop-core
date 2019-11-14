<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ConfigurableBundlePage\Reader;

use ArrayObject;
use Generated\Shared\Transfer\ConfigurableBundleTemplateStorageRequestTransfer;
use Generated\Shared\Transfer\ConfigurableBundleTemplateStorageTransfer;
use SprykerShop\Yves\ConfigurableBundlePage\Dependency\Client\ConfigurableBundlePageToConfigurableBundleStorageClientInterface;

class ConfigurableBundleTemplateStorageReader implements ConfigurableBundleTemplateStorageReaderInterface
{
    /**
     * @var \SprykerShop\Yves\ConfigurableBundlePage\Dependency\Client\ConfigurableBundlePageToConfigurableBundleStorageClientInterface
     */
    protected $configurableBundleStorageClient;

    /**
     * @param \SprykerShop\Yves\ConfigurableBundlePage\Dependency\Client\ConfigurableBundlePageToConfigurableBundleStorageClientInterface $configurableBundleStorageClient
     */
    public function __construct(ConfigurableBundlePageToConfigurableBundleStorageClientInterface $configurableBundleStorageClient)
    {
        $this->configurableBundleStorageClient = $configurableBundleStorageClient;
    }

    /**
     * @param \Generated\Shared\Transfer\ConfigurableBundleTemplateStorageRequestTransfer $configurableBundleTemplateStorageRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ConfigurableBundleTemplateStorageTransfer|null
     */
    public function getConfigurableBundleTemplateStorage(
        ConfigurableBundleTemplateStorageRequestTransfer $configurableBundleTemplateStorageRequestTransfer
    ): ?ConfigurableBundleTemplateStorageTransfer {
        $configurableBundleTemplateStorageTransfer = $this->configurableBundleStorageClient->findConfigurableBundleTemplateStorage(
            $configurableBundleTemplateStorageRequestTransfer->getIdConfigurableBundleTemplate()
        );

        if (!$configurableBundleTemplateStorageTransfer) {
            return null;
        }

        return $configurableBundleTemplateStorageTransfer->setSlots(
            $this->getIndexedConfigurableBundleTemplateStorageTransfers($configurableBundleTemplateStorageTransfer->getSlots())
        );
    }

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\ConfigurableBundleTemplateSlotStorageTransfer[] $configurableBundleTemplateSlotStorageTransfers
     *
     * @return \ArrayObject|\Generated\Shared\Transfer\ConfigurableBundleTemplateSlotStorageTransfer[]
     */
    protected function getIndexedConfigurableBundleTemplateStorageTransfers(ArrayObject $configurableBundleTemplateSlotStorageTransfers): ArrayObject
    {
        $indexedConfigurableBundleTemplateSlotStorageTransfers = new ArrayObject();

        foreach ($configurableBundleTemplateSlotStorageTransfers as $configurableBundleTemplateSlotStorageTransfer) {
            $indexedConfigurableBundleTemplateSlotStorageTransfers->offsetSet(
                $configurableBundleTemplateSlotStorageTransfer->getIdConfigurableBundleTemplateSlot(),
                $configurableBundleTemplateSlotStorageTransfer
            );
        }

        return $indexedConfigurableBundleTemplateSlotStorageTransfers;
    }
}
