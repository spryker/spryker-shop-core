<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ConfigurableBundlePage\Sanitizer;

use Generated\Shared\Transfer\ConfigurableBundleTemplateStorageTransfer;
use SprykerShop\Yves\ConfigurableBundlePage\Form\ConfiguratorStateForm;

class ConfiguratorStateSanitizer implements ConfiguratorStateSanitizerInterface
{
    /**
     * @param array $configuratorStateFormData
     * @param \Generated\Shared\Transfer\ConfigurableBundleTemplateStorageTransfer $configurableBundleTemplateStorageTransfer
     *
     * @return array
     */
    public function sanitizeConfiguratorStateFormData(
        array $configuratorStateFormData,
        ConfigurableBundleTemplateStorageTransfer $configurableBundleTemplateStorageTransfer
    ): array {
        $slotStateFormsData = $configuratorStateFormData[ConfiguratorStateForm::FIELD_SLOTS];

        $configuratorStateFormData[ConfiguratorStateForm::FIELD_SLOTS] = $this->sanitizeSlotStateFormsData(
            $slotStateFormsData,
            $configurableBundleTemplateStorageTransfer
        );

        return $configuratorStateFormData;
    }

    /**
     * @param array $slotStateFormsData
     * @param \Generated\Shared\Transfer\ConfigurableBundleTemplateStorageTransfer $configurableBundleTemplateStorageTransfer
     *
     * @return array
     */
    protected function sanitizeSlotStateFormsData(
        array $slotStateFormsData,
        ConfigurableBundleTemplateStorageTransfer $configurableBundleTemplateStorageTransfer
    ): array {
        $sanitizedSlotStateFormsData = [];

        foreach ($slotStateFormsData as $idConfigurableBundleTemplateSlot => $slotStateFormData) {
            if ($configurableBundleTemplateStorageTransfer->getSlots()->offsetExists($idConfigurableBundleTemplateSlot)) {
                $sanitizedSlotStateFormsData[$idConfigurableBundleTemplateSlot] = $slotStateFormData;
            }
        }

        return $sanitizedSlotStateFormsData;
    }
}
