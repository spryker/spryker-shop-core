<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\MerchantProfilePage;

use Spryker\Yves\Kernel\AbstractFactory;
use SprykerShop\Yves\MerchantProfilePage\Dependency\Client\MerchantProfilePageToMerchantOpeningHoursStorageClientInterface;
use SprykerShop\Yves\MerchantProfilePage\Dependency\Client\MerchantProfilePageToMerchantProfileStorageClientInterface;

class MerchantProfilePageFactory extends AbstractFactory
{
    /**
     * @return \SprykerShop\Yves\MerchantProfilePage\Dependency\Client\MerchantProfilePageToMerchantProfileStorageClientInterface
     */
    public function getMerchantStorageClient(): MerchantProfilePageToMerchantProfileStorageClientInterface
    {
        return $this->getProvidedDependency(MerchantProfilePageDependencyProvider::CLIENT_MERCHANT_PROFILE_STORAGE);
    }

    /**
     * @return \SprykerShop\Yves\MerchantProfilePage\Dependency\Client\MerchantProfilePageToMerchantOpeningHoursStorageClientInterface
     */
    public function getMerchantOpeningHoursStoregeClient(): MerchantProfilePageToMerchantOpeningHoursStorageClientInterface
    {
        return $this->getProvidedDependency(MerchantProfilePageDependencyProvider::CLIENT_MERCHANT_OPENING_HOURS_STORAGE);
    }
}
