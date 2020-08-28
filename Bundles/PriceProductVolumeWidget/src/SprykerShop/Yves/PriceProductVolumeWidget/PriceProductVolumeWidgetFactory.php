<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\PriceProductVolumeWidget;

use Spryker\Yves\Kernel\AbstractFactory;
use SprykerShop\Yves\PriceProductVolumeWidget\Dependency\Service\PriceProductVolumeWidgetToUtilEncodingServiceInterface;
use SprykerShop\Yves\PriceProductVolumeWidget\PriceProductResolver\PriceProductVolume\PriceProductVolumeResolver;
use SprykerShop\Yves\PriceProductVolumeWidget\PriceProductResolver\PriceProductVolume\PriceProductVolumeResolverInterface;

class PriceProductVolumeWidgetFactory extends AbstractFactory
{
    /**
     * @return \SprykerShop\Yves\PriceProductVolumeWidget\PriceProductResolver\PriceProductVolume\PriceProductVolumeResolverInterface
     */
    public function createPriceProductVolumeResolver(): PriceProductVolumeResolverInterface
    {
        return new PriceProductVolumeResolver($this->getUtilEncodingService());
    }

    /**
     * @return \SprykerShop\Yves\PriceProductVolumeWidget\Dependency\Service\PriceProductVolumeWidgetToUtilEncodingServiceInterface
     */
    public function getUtilEncodingService(): PriceProductVolumeWidgetToUtilEncodingServiceInterface
    {
        return $this->getProvidedDependency(PriceProductVolumeWidgetDependencyProvider::SERVICE_UTIL_ENCODING);
    }
}
