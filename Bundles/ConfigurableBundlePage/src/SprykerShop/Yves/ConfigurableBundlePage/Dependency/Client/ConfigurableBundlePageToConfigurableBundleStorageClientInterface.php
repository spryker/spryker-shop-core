<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ConfigurableBundlePage\Dependency\Client;

use Generated\Shared\Transfer\ConfigurableBundleTemplateStorageTransfer;

interface ConfigurableBundlePageToConfigurableBundleStorageClientInterface
{
    /**
     * @param int $idConfigurableBundleTemplate
     *
     * @return \Generated\Shared\Transfer\ConfigurableBundleTemplateStorageTransfer|null
     */
    public function findConfigurableBundleTemplateStorage(int $idConfigurableBundleTemplate): ?ConfigurableBundleTemplateStorageTransfer;

    /**
     * @param string[] $skus
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer[]
     */
    public function getProductConcreteStoragesBySkusForCurrentLocale(array $skus): array;
}
