<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\MerchantRelationshipPage;

use Spryker\Yves\Kernel\AbstractBundleDependencyProvider;
use Spryker\Yves\Kernel\Container;
use SprykerShop\Yves\MerchantRelationshipPage\Dependency\Client\MerchantRelationshipPageToCompanyBusinessUnitClientBridge;
use SprykerShop\Yves\MerchantRelationshipPage\Dependency\Client\MerchantRelationshipPageToCompanyUserClientBridge;
use SprykerShop\Yves\MerchantRelationshipPage\Dependency\Client\MerchantRelationshipPageToMerchantRelationshipClientBridge;
use SprykerShop\Yves\MerchantRelationshipPage\Dependency\Client\MerchantRelationshipPageToMerchantSearchClientBridge;
use SprykerShop\Yves\MerchantRelationshipPage\Dependency\Client\MerchantRelationshipPageToMerchantStorageClientBridge;

class MerchantRelationshipPageDependencyProvider extends AbstractBundleDependencyProvider
{
    /**
     * @var string
     */
    public const CLIENT_MERCHANT_RELATIONSHIP = 'CLIENT_MERCHANT_RELATIONSHIP';

    /**
     * @var string
     */
    public const CLIENT_COMPANY_USER = 'CLIENT_COMPANY_USER';

    /**
     * @var string
     */
    public const CLIENT_COMPANY_BUSINESS_UNIT = 'CLIENT_COMPANY_BUSINESS_UNIT';

    /**
     * @var string
     */
    public const CLIENT_MERCHANT_STORAGE = 'CLIENT_MERCHANT_STORAGE';

    /**
     * @var string
     */
    public const CLIENT_MERCHANT_SEARCH = 'CLIENT_MERCHANT_SEARCH';

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    public function provideDependencies(Container $container): Container
    {
        $container = parent::provideDependencies($container);
        $container = $this->addMerchantRelationshipClient($container);
        $container = $this->addCompanyUserClient($container);
        $container = $this->addCompanyBusinessUnitClient($container);
        $container = $this->addMerchantStorageClient($container);
        $container = $this->addMerchantSearchClient($container);

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addMerchantRelationshipClient(Container $container): Container
    {
        $container->set(static::CLIENT_MERCHANT_RELATIONSHIP, function (Container $container) {
            return new MerchantRelationshipPageToMerchantRelationshipClientBridge(
                $container->getLocator()->merchantRelationship()->client(),
            );
        });

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addCompanyUserClient(Container $container): Container
    {
        $container->set(static::CLIENT_COMPANY_USER, function (Container $container) {
            return new MerchantRelationshipPageToCompanyUserClientBridge(
                $container->getLocator()->companyUser()->client(),
            );
        });

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addCompanyBusinessUnitClient(Container $container): Container
    {
        $container->set(static::CLIENT_COMPANY_BUSINESS_UNIT, function (Container $container) {
            return new MerchantRelationshipPageToCompanyBusinessUnitClientBridge(
                $container->getLocator()->companyBusinessUnit()->client(),
            );
        });

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addMerchantStorageClient(Container $container): Container
    {
        $container->set(static::CLIENT_MERCHANT_STORAGE, function (Container $container) {
            return new MerchantRelationshipPageToMerchantStorageClientBridge(
                $container->getLocator()->merchantStorage()->client(),
            );
        });

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addMerchantSearchClient(Container $container): Container
    {
        $container->set(static::CLIENT_MERCHANT_SEARCH, function (Container $container) {
            return new MerchantRelationshipPageToMerchantSearchClientBridge(
                $container->getLocator()->merchantSearch()->client(),
            );
        });

        return $container;
    }
}
