<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\MerchantWidget;

use Spryker\Yves\Kernel\AbstractFactory;
use SprykerShop\Yves\MerchantWidget\Dependency\Client\MerchantWidgetToMerchantStorageClientInterface;
use SprykerShop\Yves\MerchantWidget\Reader\MerchantReader;
use SprykerShop\Yves\MerchantWidget\Reader\MerchantReaderInterface;

/**
 * @method \SprykerShop\Yves\MerchantWidget\MerchantWidgetConfig getConfig()
 */
class MerchantWidgetFactory extends AbstractFactory
{
    /**
     * @return \SprykerShop\Yves\MerchantWidget\Dependency\Client\MerchantWidgetToMerchantStorageClientInterface
     */
    public function getMerchantStorageClient(): MerchantWidgetToMerchantStorageClientInterface
    {
        return $this->getProvidedDependency(MerchantWidgetDependencyProvider::CLIENT_MERCHANT_STORAGE);
    }

    /**
     * @return \SprykerShop\Yves\MerchantWidget\Reader\MerchantReaderInterface
     */
    public function createMerchantReader(): MerchantReaderInterface
    {
        return new MerchantReader($this->getMerchantStorageClient());
    }
}
