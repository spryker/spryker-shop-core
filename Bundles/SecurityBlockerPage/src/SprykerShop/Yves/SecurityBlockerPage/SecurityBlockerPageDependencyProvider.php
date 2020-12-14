<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\SecurityBlockerPage;

use Spryker\Shared\Kernel\ContainerInterface;
use Spryker\Yves\Kernel\AbstractBundleDependencyProvider;
use Spryker\Yves\Kernel\Container;
use SprykerShop\Yves\SecurityBlockerPage\Dependency\Client\SecurityBlockerPageToSecurityBlockerClientBridge;

class SecurityBlockerPageDependencyProvider extends AbstractBundleDependencyProvider
{
    public const CLIENT_SECURITY_BLOCKER = 'CLIENT_SECURITY_BLOCKER';
    public const REQUEST_STACK = 'REQUEST_STACK';

    /**
     * @uses \Spryker\Zed\Http\Communication\Plugin\Application\HttpApplicationPlugin::SERVICE_REQUEST_STACK
     */
    public const SERVICE_REQUEST_STACK = 'request_stack';

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    public function provideDependencies(Container $container): Container
    {
        $container = parent::provideDependencies($container);
        $container = $this->addSecurityBlockerClient($container);
        $container = $this->addRequestStack($container);

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addSecurityBlockerClient(Container $container): Container
    {
        $container->set(static::CLIENT_SECURITY_BLOCKER, function (Container $container) {
            return new SecurityBlockerPageToSecurityBlockerClientBridge(
                $container->getLocator()->securityBlocker()->client()
            );
        });

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addRequestStack(Container $container): Container
    {
        $container->set(static::REQUEST_STACK, function (ContainerInterface $container) {
            return $container->getApplicationService(static::SERVICE_REQUEST_STACK);
        });

        return $container;
    }
}
