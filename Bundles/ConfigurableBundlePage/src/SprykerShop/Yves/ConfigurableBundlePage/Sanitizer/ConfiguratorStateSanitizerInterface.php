<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ConfigurableBundlePage\Sanitizer;

use Generated\Shared\Transfer\ConfigurableBundleTemplateStorageTransfer;

interface ConfiguratorStateSanitizerInterface
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
    ): array;
}
