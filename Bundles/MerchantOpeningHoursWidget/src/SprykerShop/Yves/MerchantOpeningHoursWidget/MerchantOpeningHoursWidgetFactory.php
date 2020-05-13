<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\MerchantOpeningHoursWidget;

use Spryker\Yves\Kernel\AbstractFactory;
use SprykerShop\Yves\MerchantOpeningHoursWidget\Dependency\Client\MerchantOpeningHoursWidgetToMerchantOpeningHoursStorageClientInterface;

class MerchantOpeningHoursWidgetFactory extends AbstractFactory
{
    /**
     * @return \SprykerShop\Yves\MerchantOpeningHoursWidget\Dependency\Client\MerchantOpeningHoursWidgetToMerchantOpeningHoursStorageClientInterface
     */
    public function getMerchantOpeningHoursStoregeClient(): MerchantOpeningHoursWidgetToMerchantOpeningHoursStorageClientInterface
    {
        return $this->getProvidedDependency(MerchantOpeningHoursWidgetDependencyProvider::CLIENT_MERCHANT_OPENING_HOURS_STORAGE);
    }
}
