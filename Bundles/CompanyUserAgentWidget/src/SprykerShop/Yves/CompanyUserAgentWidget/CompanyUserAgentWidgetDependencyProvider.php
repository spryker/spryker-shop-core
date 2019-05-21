<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CompanyUserAgentWidget;

use Spryker\Yves\Kernel\AbstractBundleDependencyProvider;
use Spryker\Yves\Kernel\Container;
use SprykerShop\Yves\CompanyUserAgentWidget\Dependency\Client\CompanyUserAgentWidgetToCompanyUserAgentClientBridge;

class CompanyUserAgentWidgetDependencyProvider extends AbstractBundleDependencyProvider
{
    public const CLIENT_COMPANY_USER_AGENT = 'CLIENT_COMPANY_USER_AGENT';

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    public function provideDependencies(Container $container): Container
    {
        $container = parent::provideDependencies($container);
        $container = $this->addCompanyUserAgentClient($container);

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addCompanyUserAgentClient(Container $container): Container
    {
        $container[static::CLIENT_COMPANY_USER_AGENT] = function (Container $container) {
            return new CompanyUserAgentWidgetToCompanyUserAgentClientBridge($container->getLocator()->companyUserAgent()->client());
        };

        return $container;
    }
}
