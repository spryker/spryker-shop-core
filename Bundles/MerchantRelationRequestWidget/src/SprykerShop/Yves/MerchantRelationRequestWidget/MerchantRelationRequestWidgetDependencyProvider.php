<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\MerchantRelationRequestWidget;

use Spryker\Yves\Kernel\AbstractBundleDependencyProvider;
use Spryker\Yves\Kernel\Container;
use SprykerShop\Yves\MerchantRelationRequestWidget\Dependency\Client\MerchantRelationRequestWidgetToCompanyUserClientBridge;
use SprykerShop\Yves\MerchantRelationRequestWidget\Dependency\Client\MerchantRelationRequestWidgetToMerchantStorageClientBridge;

/**
 * @method \SprykerShop\Yves\MerchantRelationRequestWidget\MerchantRelationRequestWidgetConfig getConfig()
 */
class MerchantRelationRequestWidgetDependencyProvider extends AbstractBundleDependencyProvider
{
    /**
     * @var string
     */
    public const CLIENT_MERCHANT_STORAGE = 'CLIENT_MERCHANT_STORAGE';

    /**
     * @var string
     */
    public const CLIENT_COMPANY_USER = 'CLIENT_COMPANY_USER';

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    public function provideDependencies(Container $container): Container
    {
        $container = parent::provideDependencies($container);
        $container = $this->addMerchantStorageClient($container);
        $container = $this->addCompanyUserClient($container);

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
            return new MerchantRelationRequestWidgetToMerchantStorageClientBridge(
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
    protected function addCompanyUserClient(Container $container): Container
    {
        $container->set(static::CLIENT_COMPANY_USER, function (Container $container) {
            return new MerchantRelationRequestWidgetToCompanyUserClientBridge(
                $container->getLocator()->companyUser()->client(),
            );
        });

        return $container;
    }
}
