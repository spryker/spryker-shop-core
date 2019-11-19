<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ConfigurableBundlePage\Dependency\Client;

use Generated\Shared\Transfer\ConfigurableBundleTemplateStorageTransfer;

class ConfigurableBundlePageToConfigurableBundleStorageClientBridge implements ConfigurableBundlePageToConfigurableBundleStorageClientInterface
{
    /**
     * @var \Spryker\Client\ConfigurableBundleStorage\ConfigurableBundleStorageClientInterface
     */
    protected $configurableBundleStorageClient;

    /**
     * @param \Spryker\Client\ConfigurableBundleStorage\ConfigurableBundleStorageClientInterface $configurableBundleStorageClient
     */
    public function __construct($configurableBundleStorageClient)
    {
        $this->configurableBundleStorageClient = $configurableBundleStorageClient;
    }

    /**
     * @param int $idConfigurableBundleTemplate
     *
     * @return \Generated\Shared\Transfer\ConfigurableBundleTemplateStorageTransfer|null
     */
    public function findConfigurableBundleTemplateStorage(int $idConfigurableBundleTemplate): ?ConfigurableBundleTemplateStorageTransfer
    {
        return $this->configurableBundleStorageClient->findConfigurableBundleTemplateStorage($idConfigurableBundleTemplate);
    }

    /**
     * @param string[] $skus
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer[]
     */
    public function getProductConcreteStoragesBySkusForCurrentLocale(array $skus): array
    {
        return $this->configurableBundleStorageClient->getProductConcreteStoragesBySkusForCurrentLocale($skus);
    }
}
