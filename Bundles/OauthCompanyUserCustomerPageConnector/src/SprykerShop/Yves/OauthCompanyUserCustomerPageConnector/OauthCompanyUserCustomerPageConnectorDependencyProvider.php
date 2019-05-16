<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\OauthCompanyUserCustomerPageConnector;

use Spryker\Yves\Kernel\AbstractBundleDependencyProvider;
use Spryker\Yves\Kernel\Container;
use SprykerShop\Yves\OauthCompanyUserCustomerPageConnector\Dependency\Client\OauthCompanyUserCustomerPageConnectorToOauthCompanyUserClientBridge;

class OauthCompanyUserCustomerPageConnectorDependencyProvider extends AbstractBundleDependencyProvider
{
    public const CLIENT_OAUTH_COMPANY_USER = 'CLIENT_OAUTH_COMPANY_USER';

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    public function provideDependencies(Container $container): Container
    {
        $container = parent::provideDependencies($container);
        $container = $this->addOauthCompanyUserClient($container);

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addOauthCompanyUserClient(Container $container): Container
    {
        $container[static::CLIENT_OAUTH_COMPANY_USER] = function (Container $container) {
            return new OauthCompanyUserCustomerPageConnectorToOauthCompanyUserClientBridge(
                $container->getLocator()->oauthCompanyUser()->client()
            );
        };

        return $container;
    }
}
