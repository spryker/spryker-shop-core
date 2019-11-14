<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ConfigurableBundlePage\Mapper;

use ArrayObject;
use Generated\Shared\Transfer\ConfigurableBundleTemplateSlotStorageTransfer;
use Generated\Shared\Transfer\ConfigurableBundleTemplateStorageTransfer;
use Generated\Shared\Transfer\ConfiguredBundleItemRequestTransfer;
use Generated\Shared\Transfer\ConfiguredBundleRequestTransfer;
use Generated\Shared\Transfer\CreateConfiguredBundleRequestTransfer;
use SprykerShop\Yves\ConfigurableBundlePage\Form\SlotStateForm;

class ConfiguredBundleRequestMapper implements ConfiguredBundleRequestMapperInterface
{
    protected const DEFAULT_QUANTITY = 1;

    /**
     * @param array $formData
     * @param \Generated\Shared\Transfer\ConfigurableBundleTemplateStorageTransfer $configurableBundleTemplateStorageTransfer
     * @param \Generated\Shared\Transfer\CreateConfiguredBundleRequestTransfer $createConfiguredBundleRequestTransfer
     *
     * @return \Generated\Shared\Transfer\CreateConfiguredBundleRequestTransfer
     */
    public function mapDataToCreateConfiguredBundleRequestTransfer(
        array $formData,
        ConfigurableBundleTemplateStorageTransfer $configurableBundleTemplateStorageTransfer,
        CreateConfiguredBundleRequestTransfer $createConfiguredBundleRequestTransfer
    ): CreateConfiguredBundleRequestTransfer {
        $createConfiguredBundleRequestTransfer = new CreateConfiguredBundleRequestTransfer();

        $configuredBundleRequestTransfer = $this->getMappedConfiguredBundleRequestTransfer($configurableBundleTemplateStorageTransfer);
        $createConfiguredBundleRequestTransfer->setConfiguredBundleRequest($configuredBundleRequestTransfer);

        return $this->setItemsToCreateConfiguredBundleRequestTransfer(
            $createConfiguredBundleRequestTransfer,
            $configurableBundleTemplateStorageTransfer->getSlots(),
            $formData
        );
    }

    /**
     * @param \Generated\Shared\Transfer\CreateConfiguredBundleRequestTransfer $createConfiguredBundleRequestTransfer
     * @param \ArrayObject|\Generated\Shared\Transfer\ConfigurableBundleTemplateSlotStorageTransfer[] $configurableBundleTemplateSlotStorageTransfers
     * @param array $formData
     *
     * @return \Generated\Shared\Transfer\CreateConfiguredBundleRequestTransfer
     */
    protected function setItemsToCreateConfiguredBundleRequestTransfer(
        CreateConfiguredBundleRequestTransfer $createConfiguredBundleRequestTransfer,
        ArrayObject $configurableBundleTemplateSlotStorageTransfers,
        array $formData
    ): CreateConfiguredBundleRequestTransfer {
        foreach ($configurableBundleTemplateSlotStorageTransfers as $configurableBundleTemplateSlotStorageTransfer) {
            $slotStateFormData = $formData[$configurableBundleTemplateSlotStorageTransfer->getIdConfigurableBundleTemplateSlot()] ?? null;

            if (!$slotStateFormData) {
                continue;
            }

            $createConfiguredBundleRequestTransfer->addItem(
                $this->getMappedConfiguredBundleItemRequestTransfer($slotStateFormData, $configurableBundleTemplateSlotStorageTransfer)
            );
        }

        return $createConfiguredBundleRequestTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ConfigurableBundleTemplateStorageTransfer $configurableBundleTemplateStorageTransfer
     *
     * @return \Generated\Shared\Transfer\ConfiguredBundleRequestTransfer
     */
    protected function getMappedConfiguredBundleRequestTransfer(ConfigurableBundleTemplateStorageTransfer $configurableBundleTemplateStorageTransfer): ConfiguredBundleRequestTransfer
    {
        return (new ConfiguredBundleRequestTransfer())
            ->setTemplateName($configurableBundleTemplateStorageTransfer->getName())
            ->setTemplateUuid($configurableBundleTemplateStorageTransfer->getUuid())
            ->setQuantity(static::DEFAULT_QUANTITY);
    }

    /**
     * @param array $slotStateFormData
     * @param \Generated\Shared\Transfer\ConfigurableBundleTemplateSlotStorageTransfer $configurableBundleTemplateSlotStorageTransfer
     *
     * @return \Generated\Shared\Transfer\ConfiguredBundleItemRequestTransfer
     */
    protected function getMappedConfiguredBundleItemRequestTransfer(
        array $slotStateFormData,
        ConfigurableBundleTemplateSlotStorageTransfer $configurableBundleTemplateSlotStorageTransfer
    ): ConfiguredBundleItemRequestTransfer {
        return (new ConfiguredBundleItemRequestTransfer())
            ->setSku($slotStateFormData[SlotStateForm::FILED_SKU])
            ->setSlotUuid($configurableBundleTemplateSlotStorageTransfer->getUuid())
            ->setQuantity(static::DEFAULT_QUANTITY);
    }
}
