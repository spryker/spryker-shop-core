<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ConfigurableBundlePage\Reader;

use Generated\Shared\Transfer\ConfigurableBundleTemplateStorageRequestTransfer;
use Generated\Shared\Transfer\ConfigurableBundleTemplateStorageTransfer;

interface ConfigurableBundleTemplateStorageReaderInterface
{
    /**
     * @param \Generated\Shared\Transfer\ConfigurableBundleTemplateStorageRequestTransfer $configurableBundleTemplateStorageRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ConfigurableBundleTemplateStorageTransfer|null
     */
    public function getConfigurableBundleTemplateStorage(
        ConfigurableBundleTemplateStorageRequestTransfer $configurableBundleTemplateStorageRequestTransfer
    ): ?ConfigurableBundleTemplateStorageTransfer;
}
