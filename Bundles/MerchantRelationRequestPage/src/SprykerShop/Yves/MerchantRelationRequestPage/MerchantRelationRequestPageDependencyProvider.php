<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\MerchantRelationRequestPage;

use Spryker\Yves\Kernel\AbstractBundleDependencyProvider;
use Spryker\Yves\Kernel\Container;
use SprykerShop\Yves\MerchantRelationRequestPage\Dependency\Client\MerchantRelationRequestPageToCompanyBusinessUnitClientBridge;
use SprykerShop\Yves\MerchantRelationRequestPage\Dependency\Client\MerchantRelationRequestPageToCompanyUserClientBridge;
use SprykerShop\Yves\MerchantRelationRequestPage\Dependency\Client\MerchantRelationRequestPageToCustomerClientBridge;
use SprykerShop\Yves\MerchantRelationRequestPage\Dependency\Client\MerchantRelationRequestPageToMerchantRelationRequestClientBridge;
use SprykerShop\Yves\MerchantRelationRequestPage\Dependency\Client\MerchantRelationRequestPageToMerchantSearchClientBridge;
use SprykerShop\Yves\MerchantRelationRequestPage\Dependency\Client\MerchantRelationRequestPageToMerchantStorageClientBridge;

/**
 * @method \SprykerShop\Yves\MerchantRelationRequestPage\MerchantRelationRequestPageConfig getConfig()
 */
class MerchantRelationRequestPageDependencyProvider extends AbstractBundleDependencyProvider
{
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
    public const CLIENT_CUSTOMER = 'CLIENT_CUSTOMER';

    /**
     * @var string
     */
    public const CLIENT_MERCHANT_SEARCH = 'CLIENT_MERCHANT_SEARCH';

    /**
     * @var string
     */
    public const CLIENT_MERCHANT_STORAGE = 'CLIENT_MERCHANT_STORAGE';

    /**
     * @var string
     */
    public const CLIENT_MERCHANT_RELATION_REQUEST = 'CLIENT_MERCHANT_RELATION_REQUEST';

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    public function provideDependencies(Container $container): Container
    {
        $container = parent::provideDependencies($container);
        $container = $this->addCompanyUserClient($container);
        $container = $this->addCompanyBusinessUnitClient($container);
        $container = $this->addCustomerClient($container);
        $container = $this->addMerchantSearchClient($container);
        $container = $this->addMerchantStorageClient($container);
        $container = $this->addMerchantRelationRequestClient($container);

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
            return new MerchantRelationRequestPageToCompanyUserClientBridge($container->getLocator()->companyUser()->client());
        });

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addCustomerClient(Container $container): Container
    {
        $container->set(static::CLIENT_CUSTOMER, function (Container $container) {
            return new MerchantRelationRequestPageToCustomerClientBridge($container->getLocator()->customer()->client());
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
            return new MerchantRelationRequestPageToMerchantSearchClientBridge(
                $container->getLocator()->merchantSearch()->client(),
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
            return new MerchantRelationRequestPageToMerchantStorageClientBridge(
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
    protected function addMerchantRelationRequestClient(Container $container): Container
    {
        $container->set(static::CLIENT_MERCHANT_RELATION_REQUEST, function (Container $container) {
            return new MerchantRelationRequestPageToMerchantRelationRequestClientBridge(
                $container->getLocator()->merchantRelationRequest()->client(),
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
            return new MerchantRelationRequestPageToCompanyBusinessUnitClientBridge(
                $container->getLocator()->companyBusinessUnit()->client(),
            );
        });

        return $container;
    }
}
