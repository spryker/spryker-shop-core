<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductSetDetailPage;

use Spryker\Yves\Kernel\AbstractFactory;
use SprykerShop\Yves\ProductSetDetailPage\Dependency\Client\ProductSetDetailPageToProductSetStorageClientInterface;
use SprykerShop\Yves\ProductSetDetailPage\Dependency\Client\ProductSetDetailPageToProductStorageClientInterface;
use SprykerShop\Yves\ProductSetDetailPage\OptionResetUrlGenerator\OptionResetUrlGenerator;
use SprykerShop\Yves\ProductSetDetailPage\OptionResetUrlGenerator\OptionResetUrlGeneratorInterface;

class ProductSetDetailPageFactory extends AbstractFactory
{
    /**
     * @return \SprykerShop\Yves\ProductSetDetailPage\Dependency\Client\ProductSetDetailPageToProductSetStorageClientInterface
     */
    public function getProductSetStorageClient(): ProductSetDetailPageToProductSetStorageClientInterface
    {
        return $this->getProvidedDependency(ProductSetDetailPageDependencyProvider::CLIENT_PRODUCT_SET_STORAGE);
    }

    /**
     * @return \SprykerShop\Yves\ProductSetDetailPage\Dependency\Client\ProductSetDetailPageToProductStorageClientInterface
     */
    public function getProductStorageClient(): ProductSetDetailPageToProductStorageClientInterface
    {
        return $this->getProvidedDependency(ProductSetDetailPageDependencyProvider::CLIENT_PRODUCT_STORAGE);
    }

    /**
     * @return string[]
     */
    public function getProductSetDetailPageWidgetPlugins(): array
    {
        return $this->getProvidedDependency(ProductSetDetailPageDependencyProvider::PLUGIN_PRODUCT_SET_DETAIL_PAGE_WIDGETS);
    }

    /**
     * @return \SprykerShop\Yves\ProductSetDetailPage\OptionResetUrlGenerator\OptionResetUrlGeneratorInterface
     */
    public function createOptionResetUrlGenerator(): OptionResetUrlGeneratorInterface
    {
        return new OptionResetUrlGenerator();
    }
}
