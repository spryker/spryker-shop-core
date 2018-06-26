<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\VolumePriceProductWidget;

use Spryker\Yves\Kernel\AbstractFactory;
use SprykerShop\Yves\VolumePriceProductWidget\Business\VolumePriceProduct\VolumePriceProductResolver;
use SprykerShop\Yves\VolumePriceProductWidget\Business\VolumePriceProduct\VolumePriceProductResolverInterface;
use SprykerShop\Yves\VolumePriceProductWidget\Dependency\Client\VolumePriceProductWidgetToCurrencyClientInterface;
use SprykerShop\Yves\VolumePriceProductWidget\Dependency\Client\VolumePriceProductWidgetToPriceClientInterface;
use SprykerShop\Yves\VolumePriceProductWidget\Dependency\Client\VolumePriceProductWidgetToPriceProductStorageClientInterface;

class VolumePriceProductWidgetFactory extends AbstractFactory
{
    /**
     * @return \SprykerShop\Yves\VolumePriceProductWidget\Business\VolumePriceProduct\VolumePriceProductResolverInterface
     */
    public function createVolumePriceProductResolver(): VolumePriceProductResolverInterface
    {
        return new VolumePriceProductResolver(
            $this->getProductStorageClient(),
            $this->getPriceClient(),
            $this->getCurrencyClient()
        );
    }

    /**
     * @return \SprykerShop\Yves\VolumePriceProductWidget\Dependency\Client\VolumePriceProductWidgetToPriceProductStorageClientInterface
     */
    public function getProductStorageClient(): VolumePriceProductWidgetToPriceProductStorageClientInterface
    {
        return $this->getProvidedDependency(VolumePriceProductWidgetDependencyProvider::CLIENT_PRODUCT_STORAGE);
    }

    /**
     * @return \SprykerShop\Yves\VolumePriceProductWidget\Dependency\Client\VolumePriceProductWidgetToPriceClientInterface
     */
    public function getPriceClient(): VolumePriceProductWidgetToPriceClientInterface
    {
        return $this->getProvidedDependency(VolumePriceProductWidgetDependencyProvider::CLIENT_PRICE);
    }

    /**
     * @return \SprykerShop\Yves\VolumePriceProductWidget\Dependency\Client\VolumePriceProductWidgetToCurrencyClientInterface
     */
    public function getCurrencyClient(): VolumePriceProductWidgetToCurrencyClientInterface
    {
        return $this->getProvidedDependency(VolumePriceProductWidgetDependencyProvider::CLIENT_CURRENCY);
    }
}
