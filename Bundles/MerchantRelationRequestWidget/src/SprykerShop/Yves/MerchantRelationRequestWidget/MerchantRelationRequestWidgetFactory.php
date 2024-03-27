<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\MerchantRelationRequestWidget;

use Spryker\Yves\Kernel\AbstractFactory;
use SprykerShop\Yves\MerchantRelationRequestWidget\Checker\MerchantRelationRequestChecker;
use SprykerShop\Yves\MerchantRelationRequestWidget\Checker\MerchantRelationRequestCheckerInterface;
use SprykerShop\Yves\MerchantRelationRequestWidget\Dependency\Client\MerchantRelationRequestWidgetToCompanyUserClientInterface;
use SprykerShop\Yves\MerchantRelationRequestWidget\Dependency\Client\MerchantRelationRequestWidgetToMerchantStorageClientInterface;

/**
 * @method \SprykerShop\Yves\MerchantRelationRequestWidget\MerchantRelationRequestWidgetConfig getConfig()
 */
class MerchantRelationRequestWidgetFactory extends AbstractFactory
{
    /**
     * @return \SprykerShop\Yves\MerchantRelationRequestWidget\Checker\MerchantRelationRequestCheckerInterface
     */
    public function createMerchantRelationRequestChecker(): MerchantRelationRequestCheckerInterface
    {
        return new MerchantRelationRequestChecker(
            $this->getMerchantStorageClient(),
        );
    }

    /**
     * @return \SprykerShop\Yves\MerchantRelationRequestWidget\Dependency\Client\MerchantRelationRequestWidgetToMerchantStorageClientInterface
     */
    public function getMerchantStorageClient(): MerchantRelationRequestWidgetToMerchantStorageClientInterface
    {
        return $this->getProvidedDependency(MerchantRelationRequestWidgetDependencyProvider::CLIENT_MERCHANT_STORAGE);
    }

    /**
     * @return \SprykerShop\Yves\MerchantRelationRequestWidget\Dependency\Client\MerchantRelationRequestWidgetToCompanyUserClientInterface
     */
    public function getCompanyUserClient(): MerchantRelationRequestWidgetToCompanyUserClientInterface
    {
        return $this->getProvidedDependency(MerchantRelationRequestWidgetDependencyProvider::CLIENT_COMPANY_USER);
    }
}
