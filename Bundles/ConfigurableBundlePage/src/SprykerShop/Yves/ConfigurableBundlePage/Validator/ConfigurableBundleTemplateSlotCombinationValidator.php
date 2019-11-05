<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ConfigurableBundlePage\Validator;

use Generated\Shared\Transfer\ConfigurableBundleTemplateStorageTransfer;

class ConfigurableBundleTemplateSlotCombinationValidator implements ConfigurableBundleTemplateSlotCombinationValidatorInterface
{
    /**
     * @param \Generated\Shared\Transfer\ConfigurableBundleTemplateStorageTransfer $configurableBundleTemplateStorageTransfer
     * @param int $idConfigurableBundleTemplateSlot
     *
     * @return bool
     */
    public function validateConfigurableBundleTemplateSlotCombination(
        ConfigurableBundleTemplateStorageTransfer $configurableBundleTemplateStorageTransfer,
        int $idConfigurableBundleTemplateSlot
    ): bool {
        $configurableBundleTemplateSlotIds = $this->extractConfigurableBundleTemplateSlotIds($configurableBundleTemplateStorageTransfer);

        return in_array($idConfigurableBundleTemplateSlot, $configurableBundleTemplateSlotIds, true);
    }

    /**
     * @param \Generated\Shared\Transfer\ConfigurableBundleTemplateStorageTransfer $configurableBundleTemplateStorageTransfer
     *
     * @return int[]
     */
    protected function extractConfigurableBundleTemplateSlotIds(ConfigurableBundleTemplateStorageTransfer $configurableBundleTemplateStorageTransfer): array
    {
        $configurableBundleTemplateSlotIds = [];

        foreach ($configurableBundleTemplateStorageTransfer->getSlots() as $configurableBundleTemplateSlotStorageTransfer) {
            $configurableBundleTemplateSlotIds[] = $configurableBundleTemplateSlotStorageTransfer->getIdConfigurableBundleTemplateSlot();
        }

        return $configurableBundleTemplateSlotIds;
    }
}
