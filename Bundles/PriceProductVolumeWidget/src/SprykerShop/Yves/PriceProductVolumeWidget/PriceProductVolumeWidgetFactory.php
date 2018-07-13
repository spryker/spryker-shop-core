<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\PriceProductVolumeWidget;

use Spryker\Yves\Kernel\AbstractFactory;
use SprykerShop\Yves\PriceProductVolumeWidget\Business\PriceProductVolume\PriceProductVolumeResolver;
use SprykerShop\Yves\PriceProductVolumeWidget\Business\PriceProductVolume\PriceProductVolumeResolverInterface;
use SprykerShop\Yves\PriceProductVolumeWidget\Dependency\Client\PriceProductVolumeWidgetToCurrencyClientInterface;
use SprykerShop\Yves\PriceProductVolumeWidget\Dependency\Client\PriceProductVolumeWidgetToPriceClientInterface;
use SprykerShop\Yves\PriceProductVolumeWidget\Dependency\Client\PriceProductVolumeWidgetToPriceProductStorageClientInterface;
use SprykerShop\Yves\PriceProductVolumeWidget\Dependency\Service\PriceProductVolumeWidgetToUtilEncodingServiceInterface;

class PriceProductVolumeWidgetFactory extends AbstractFactory
{
    /**
     * @return \SprykerShop\Yves\PriceProductVolumeWidget\Business\PriceProductVolume\PriceProductVolumeResolverInterface
     */
    public function createPriceProductVolumeResolver(): PriceProductVolumeResolverInterface
    {
        return new PriceProductVolumeResolver(
            $this->getProductStorageClient(),
            $this->getPriceClient(),
            $this->getCurrencyClient(),
            $this->getUtilEncodingService()
        );
    }

    /**
     * @return \SprykerShop\Yves\PriceProductVolumeWidget\Dependency\Client\PriceProductVolumeWidgetToPriceProductStorageClientInterface
     */
    public function getProductStorageClient(): PriceProductVolumeWidgetToPriceProductStorageClientInterface
    {
        return $this->getProvidedDependency(PriceProductVolumeWidgetDependencyProvider::CLIENT_PRODUCT_STORAGE);
    }

    /**
     * @return \SprykerShop\Yves\PriceProductVolumeWidget\Dependency\Client\PriceProductVolumeWidgetToPriceClientInterface
     */
    public function getPriceClient(): PriceProductVolumeWidgetToPriceClientInterface
    {
        return $this->getProvidedDependency(PriceProductVolumeWidgetDependencyProvider::CLIENT_PRICE);
    }

    /**
     * @return \SprykerShop\Yves\PriceProductVolumeWidget\Dependency\Client\PriceProductVolumeWidgetToCurrencyClientInterface
     */
    public function getCurrencyClient(): PriceProductVolumeWidgetToCurrencyClientInterface
    {
        return $this->getProvidedDependency(PriceProductVolumeWidgetDependencyProvider::CLIENT_CURRENCY);
    }

    /**
     * @return \SprykerShop\Yves\PriceProductVolumeWidget\Dependency\Service\PriceProductVolumeWidgetToUtilEncodingServiceInterface
     */
    public function getUtilEncodingService(): PriceProductVolumeWidgetToUtilEncodingServiceInterface
    {
        return $this->getProvidedDependency(PriceProductVolumeWidgetDependencyProvider::SERVICE_UTIL_ENCODING);
    }
}
