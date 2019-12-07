<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ConfigurableBundlePage\Sanitizer;

use ArrayObject;
use Generated\Shared\Transfer\ConfiguratorStateTransfer;
use Generated\Shared\Transfer\MessageTransfer;
use SprykerShop\Yves\ConfigurableBundlePage\Form\SlotStateForm;

class ConfiguratorStateSanitizer implements ConfiguratorStateSanitizerInterface
{
    protected const GLOSSARY_KEY_SLOT_BECAME_UNAVAILABLE = 'configurable_bundle_page.configurator.slot_became_unavailable';
    protected const GLOSSARY_KEY_PRODUCT_BECAME_UNAVAILABLE = 'configurable_bundle_page.configurator.product_became_unavailable';
    protected const GLOSSARY_PARAMETER_ID = '%id%';
    protected const GLOSSARY_PARAMETER_SKU = '%sku%';

    /**
     * @param \Generated\Shared\Transfer\ConfiguratorStateTransfer $configuratorStateTransfer
     *
     * @return \Generated\Shared\Transfer\ConfiguratorStateTransfer
     */
    public function sanitizeConfiguratorStateFormData(ConfiguratorStateTransfer $configuratorStateTransfer): ConfiguratorStateTransfer
    {
        $configuratorStateTransfer = $this->sanitizeSlots($configuratorStateTransfer);
        $configuratorStateTransfer = $this->sanitizeProducts($configuratorStateTransfer);

        $configuratorStateTransfer->setIsStateModified(
            (bool)$configuratorStateTransfer->getMessages()->count()
        );

        return $configuratorStateTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ConfiguratorStateTransfer $configuratorStateTransfer
     *
     * @return \Generated\Shared\Transfer\ConfiguratorStateTransfer
     */
    protected function sanitizeSlots(ConfiguratorStateTransfer $configuratorStateTransfer): ConfiguratorStateTransfer
    {
        $sanitizedSlotStateFormsData = [];
        $configurableBundleTemplateSlotStorageTransfers = $configuratorStateTransfer->getConfigurableBundleTemplateStorage()->getSlots();

        foreach ($configuratorStateTransfer->getSlotStateFormsData() as $idConfigurableBundleTemplateSlot => $slotStateFormData) {
            if (!$configurableBundleTemplateSlotStorageTransfers->offsetExists($idConfigurableBundleTemplateSlot)) {
                $configuratorStateTransfer->addMessage(
                    (new MessageTransfer())
                        ->setValue(static::GLOSSARY_KEY_SLOT_BECAME_UNAVAILABLE)
                        ->setParameters([
                            static::GLOSSARY_PARAMETER_ID => $idConfigurableBundleTemplateSlot,
                        ])
                );

                continue;
            }

            $sku = $slotStateFormData[SlotStateForm::FIELD_SKU];

            if (!$configuratorStateTransfer->getProducts()->offsetExists($sku)) {
                $configuratorStateTransfer->addMessage(
                    (new MessageTransfer())
                        ->setValue(static::GLOSSARY_KEY_PRODUCT_BECAME_UNAVAILABLE)
                        ->setParameters([
                            static::GLOSSARY_PARAMETER_ID => $idConfigurableBundleTemplateSlot,
                            static::GLOSSARY_PARAMETER_SKU => $sku,
                        ])
                );

                continue;
            }

            $sanitizedSlotStateFormsData[$idConfigurableBundleTemplateSlot] = $slotStateFormData;
        }

        return $configuratorStateTransfer->setSlotStateFormsData($sanitizedSlotStateFormsData);
    }

    /**
     * @param \Generated\Shared\Transfer\ConfiguratorStateTransfer $configuratorStateTransfer
     *
     * @return \Generated\Shared\Transfer\ConfiguratorStateTransfer
     */
    protected function sanitizeProducts(ConfiguratorStateTransfer $configuratorStateTransfer): ConfiguratorStateTransfer
    {
        $sanitizedProductViewTransfers = new ArrayObject();

        foreach ($configuratorStateTransfer->getConfigurableBundleTemplateStorage()->getSlots() as $configurableBundleTemplateSlotStorageTransfer) {
            $idConfigurableBundleTemplateSlot = $configurableBundleTemplateSlotStorageTransfer->getIdConfigurableBundleTemplateSlot();
            $slotStateFormsData = $configuratorStateTransfer->getSlotStateFormsData();

            if (!isset($slotStateFormsData[$idConfigurableBundleTemplateSlot])) {
                continue;
            }

            $productViewTransfers = $configuratorStateTransfer->getProducts();
            $sku = $slotStateFormsData[$idConfigurableBundleTemplateSlot][SlotStateForm::FIELD_SKU];

            if (isset($slotStateFormsData[$idConfigurableBundleTemplateSlot]) && $productViewTransfers->offsetExists($sku)) {
                $sanitizedProductViewTransfers->offsetSet(
                    $idConfigurableBundleTemplateSlot,
                    $productViewTransfers->offsetGet($sku)
                );
            }
        }

        return $configuratorStateTransfer->setProducts($sanitizedProductViewTransfers);
    }
}
