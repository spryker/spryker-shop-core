<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ConfigurableBundlePage\Sanitizer;

use Generated\Shared\Transfer\ConfiguratorStateSanitizeRequestTransfer;
use Generated\Shared\Transfer\ConfiguratorStateSanitizeResponseTransfer;
use Generated\Shared\Transfer\MessageTransfer;
use SprykerShop\Yves\ConfigurableBundlePage\Form\SlotStateForm;

class ConfiguratorStateSanitizer implements ConfiguratorStateSanitizerInterface
{
    protected const GLOSSARY_KEY_SLOT_BECAME_UNAVAILABLE = 'configurable_bundle_page.configurator.slot_became_unavailable';
    protected const GLOSSARY_KEY_PRODUCT_BECAME_UNAVAILABLE = 'configurable_bundle_page.configurator.product_became_unavailable';
    protected const GLOSSARY_PARAMETER_ID = '%id%';
    protected const GLOSSARY_PARAMETER_SKU = '%sku%';

    /**
     * @param \Generated\Shared\Transfer\ConfiguratorStateSanitizeRequestTransfer $configuratorStateSanitizeRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ConfiguratorStateSanitizeResponseTransfer
     */
    public function sanitizeConfiguratorStateFormData(ConfiguratorStateSanitizeRequestTransfer $configuratorStateSanitizeRequestTransfer): ConfiguratorStateSanitizeResponseTransfer
    {
        $configuratorStateSanitizeResponseTransfer = new ConfiguratorStateSanitizeResponseTransfer();

        $configuratorStateSanitizeResponseTransfer = $this->sanitizeSlots(
            $configuratorStateSanitizeRequestTransfer,
            $configuratorStateSanitizeResponseTransfer
        );

        $configuratorStateSanitizeResponseTransfer = $this->sanitizeProducts(
            $configuratorStateSanitizeRequestTransfer,
            $configuratorStateSanitizeResponseTransfer
        );

        $configuratorStateSanitizeResponseTransfer->setIsSanitized(
            (bool)$configuratorStateSanitizeResponseTransfer->getMessages()->count()
        );

        return $configuratorStateSanitizeResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ConfiguratorStateSanitizeRequestTransfer $configuratorStateSanitizeRequestTransfer
     * @param \Generated\Shared\Transfer\ConfiguratorStateSanitizeResponseTransfer $configuratorStateSanitizeResponseTransfer
     *
     * @return \Generated\Shared\Transfer\ConfiguratorStateSanitizeResponseTransfer
     */
    protected function sanitizeSlots(
        ConfiguratorStateSanitizeRequestTransfer $configuratorStateSanitizeRequestTransfer,
        ConfiguratorStateSanitizeResponseTransfer $configuratorStateSanitizeResponseTransfer
    ): ConfiguratorStateSanitizeResponseTransfer {
        $configuratorStateSanitizeRequestTransfer
            ->requireSlotStateFormsData()
            ->requireConfigurableBundleTemplateStorage();

        $sanitizedSlotStateFormsData = [];
        $configurableBundleTemplateStorageTransfer = $configuratorStateSanitizeRequestTransfer->getConfigurableBundleTemplateStorage();
        $productViewTransfers = $configuratorStateSanitizeRequestTransfer->getProducts();

        foreach ($configuratorStateSanitizeRequestTransfer->getSlotStateFormsData() as $idConfigurableBundleTemplateSlot => $slotStateFormData) {
            if (!$configurableBundleTemplateStorageTransfer->getSlots()->offsetExists($idConfigurableBundleTemplateSlot)) {
                $configuratorStateSanitizeResponseTransfer->addMessage(
                    (new MessageTransfer())
                        ->setValue(static::GLOSSARY_KEY_SLOT_BECAME_UNAVAILABLE)
                        ->setParameters([
                            static::GLOSSARY_PARAMETER_ID => $idConfigurableBundleTemplateSlot,
                        ])
                );

                continue;
            }

            $sku = $slotStateFormData[SlotStateForm::FIELD_SKU];

            if (!$productViewTransfers->offsetExists($sku)) {
                $configuratorStateSanitizeResponseTransfer->addMessage(
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

        return $configuratorStateSanitizeResponseTransfer->setSanitizedSlotStateFormsData($sanitizedSlotStateFormsData);
    }

    /**
     * @param \Generated\Shared\Transfer\ConfiguratorStateSanitizeRequestTransfer $configuratorStateSanitizeRequestTransfer
     * @param \Generated\Shared\Transfer\ConfiguratorStateSanitizeResponseTransfer $configuratorStateSanitizeResponseTransfer
     *
     * @return \Generated\Shared\Transfer\ConfiguratorStateSanitizeResponseTransfer
     */
    protected function sanitizeProducts(
        ConfiguratorStateSanitizeRequestTransfer $configuratorStateSanitizeRequestTransfer,
        ConfiguratorStateSanitizeResponseTransfer $configuratorStateSanitizeResponseTransfer
    ): ConfiguratorStateSanitizeResponseTransfer {
        $slotStateFormsData = $configuratorStateSanitizeResponseTransfer->getSanitizedSlotStateFormsData();
        $configurableBundleTemplateSlotStorageTransfers = $configuratorStateSanitizeRequestTransfer->getConfigurableBundleTemplateStorage()->getSlots();
        $productViewTransfers = $configuratorStateSanitizeRequestTransfer->getProducts();

        foreach ($configurableBundleTemplateSlotStorageTransfers as $configurableBundleTemplateSlotStorageTransfer) {
            $idConfigurableBundleTemplateSlot = $configurableBundleTemplateSlotStorageTransfer->getIdConfigurableBundleTemplateSlot();

            if (isset($slotStateFormsData[$idConfigurableBundleTemplateSlot])) {
                $sku = $slotStateFormsData[$idConfigurableBundleTemplateSlot][SlotStateForm::FIELD_SKU];

                $configuratorStateSanitizeResponseTransfer->getSanitizedProducts()->offsetSet(
                    $idConfigurableBundleTemplateSlot,
                    $productViewTransfers[$sku]
                );
            }
        }

        return $configuratorStateSanitizeResponseTransfer;
    }
}
