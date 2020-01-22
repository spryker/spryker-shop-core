<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ConfigurableBundlePage\Mapper;

use Generated\Shared\Transfer\ConfigurableBundleTemplateStorageTransfer;
use Generated\Shared\Transfer\CreateConfiguredBundleRequestTransfer;

interface ConfiguredBundleRequestMapperInterface
{
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
    ): CreateConfiguredBundleRequestTransfer;
}
