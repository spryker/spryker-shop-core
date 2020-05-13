<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ConfigurableBundlePage\Mapper;

use ArrayObject;
use Generated\Shared\Transfer\ConfigurableBundleTemplateSlotStorageTransfer;
use Generated\Shared\Transfer\ConfigurableBundleTemplateSlotTransfer;
use Generated\Shared\Transfer\ConfigurableBundleTemplateStorageTransfer;
use Generated\Shared\Transfer\ConfigurableBundleTemplateTransfer;
use Generated\Shared\Transfer\ConfiguredBundleItemTransfer;
use Generated\Shared\Transfer\ConfiguredBundleTransfer;
use Generated\Shared\Transfer\CreateConfiguredBundleRequestTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use SprykerShop\Yves\ConfigurableBundlePage\Expander\ItemExpanderInterface;
use SprykerShop\Yves\ConfigurableBundlePage\Form\SlotStateForm;

class ConfiguredBundleRequestMapper implements ConfiguredBundleRequestMapperInterface
{
    protected const DEFAULT_QUANTITY = 1;

    /**
     * @var \SprykerShop\Yves\ConfigurableBundlePage\Expander\ItemExpanderInterface
     */
    protected $itemExpander;

    /**
     * @param \SprykerShop\Yves\ConfigurableBundlePage\Expander\ItemExpanderInterface $itemExpander
     */
    public function __construct(ItemExpanderInterface $itemExpander)
    {
        $this->itemExpander = $itemExpander;
    }

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
        $createConfiguredBundleRequestTransfer->requireLocaleName();

        $configuredBundleTransfer = $this->getMappedConfiguredBundleTransfer($configurableBundleTemplateStorageTransfer);
        $createConfiguredBundleRequestTransfer->setConfiguredBundle($configuredBundleTransfer);

        $createConfiguredBundleRequestTransfer = $this->setItemsToCreateConfiguredBundleRequestTransfer(
            $createConfiguredBundleRequestTransfer,
            $configurableBundleTemplateStorageTransfer->getSlots(),
            $formData
        );

        $itemTransfers = $this->itemExpander->expandItemTransfers(
            $createConfiguredBundleRequestTransfer->getItems(),
            $createConfiguredBundleRequestTransfer->getLocaleName()
        );

        return $createConfiguredBundleRequestTransfer->setItems($itemTransfers);
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
                $this->getMappedItemTransfer($slotStateFormData, $configurableBundleTemplateSlotStorageTransfer)
            );
        }

        return $createConfiguredBundleRequestTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ConfigurableBundleTemplateStorageTransfer $configurableBundleTemplateStorageTransfer
     *
     * @return \Generated\Shared\Transfer\ConfiguredBundleTransfer
     */
    protected function getMappedConfiguredBundleTransfer(
        ConfigurableBundleTemplateStorageTransfer $configurableBundleTemplateStorageTransfer
    ): ConfiguredBundleTransfer {
        $configurableBundleTransfer = (new ConfigurableBundleTemplateTransfer())->fromArray(
            $configurableBundleTemplateStorageTransfer->toArray(),
            true
        );

        return (new ConfiguredBundleTransfer())
            ->setTemplate($configurableBundleTransfer)
            ->setQuantity(static::DEFAULT_QUANTITY);
    }

    /**
     * @param array $slotStateFormData
     * @param \Generated\Shared\Transfer\ConfigurableBundleTemplateSlotStorageTransfer $configurableBundleTemplateSlotStorageTransfer
     *
     * @return \Generated\Shared\Transfer\ItemTransfer
     */
    protected function getMappedItemTransfer(
        array $slotStateFormData,
        ConfigurableBundleTemplateSlotStorageTransfer $configurableBundleTemplateSlotStorageTransfer
    ): ItemTransfer {
        $configurableBundleTemplateSlotTransfer = (new ConfigurableBundleTemplateSlotTransfer())->fromArray(
            $configurableBundleTemplateSlotStorageTransfer->toArray(),
            true
        );

        return (new ItemTransfer())
            ->setSku($slotStateFormData[SlotStateForm::FIELD_SKU])
            ->setConfiguredBundleItem((new ConfiguredBundleItemTransfer())->setSlot($configurableBundleTemplateSlotTransfer))
            ->setQuantity(static::DEFAULT_QUANTITY);
    }
}
