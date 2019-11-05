<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ConfigurableBundlePage\Validator;

use Generated\Shared\Transfer\ConfigurableBundleTemplateStorageTransfer;

interface ConfigurableBundleTemplateSlotCombinationValidatorInterface
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
    ): bool;
}
