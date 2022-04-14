<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\AssetWidget;

use Spryker\Yves\Kernel\AbstractFactory;
use SprykerShop\Yves\AssetWidget\Business\AssetWidgetDataProvider;
use SprykerShop\Yves\AssetWidget\Business\AssetWidgetDataProviderInterface;
use SprykerShop\Yves\AssetWidget\Dependency\Client\AssetWidgetToAssetStorageClientInterface;
use SprykerShop\Yves\AssetWidget\Dependency\Client\AssetWidgetToStoreClientInterface;

/**
 * @method \SprykerShop\Yves\AssetWidget\AssetWidgetConfig getConfig()
 */
class AssetWidgetFactory extends AbstractFactory
{
    /**
     * @return \SprykerShop\Yves\AssetWidget\Business\AssetWidgetDataProviderInterface
     */
    public function createAssetWidgetDataProvider(): AssetWidgetDataProviderInterface
    {
        return new AssetWidgetDataProvider(
            $this->getAssetStorageClient(),
            $this->getStoreClient(),
        );
    }

    /**
     * @return \SprykerShop\Yves\AssetWidget\Dependency\Client\AssetWidgetToAssetStorageClientInterface
     */
    public function getAssetStorageClient(): AssetWidgetToAssetStorageClientInterface
    {
        return $this->getProvidedDependency(AssetWidgetDependencyProvider::CLIENT_ASSET_STORAGE);
    }

    /**
     * @return \SprykerShop\Yves\AssetWidget\Dependency\Client\AssetWidgetToStoreClientInterface
     */
    public function getStoreClient(): AssetWidgetToStoreClientInterface
    {
        return $this->getProvidedDependency(AssetWidgetDependencyProvider::CLIENT_STORE);
    }
}
