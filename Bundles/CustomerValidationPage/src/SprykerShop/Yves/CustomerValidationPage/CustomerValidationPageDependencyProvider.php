<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CustomerValidationPage;

use Spryker\Shared\Kernel\ContainerInterface;
use Spryker\Yves\Kernel\AbstractBundleDependencyProvider;
use Spryker\Yves\Kernel\Container;
use SprykerShop\Yves\CustomerValidationPage\Dependency\Client\CustomerValidationPageToCustomerClientBridge;
use SprykerShop\Yves\CustomerValidationPage\Dependency\Client\CustomerValidationPageToCustomerClientInterface;
use SprykerShop\Yves\CustomerValidationPage\Dependency\Client\CustomerValidationPageToCustomerStorageClientBridge;
use SprykerShop\Yves\CustomerValidationPage\Dependency\Client\CustomerValidationPageToCustomerStorageClientInterface;
use SprykerShop\Yves\CustomerValidationPage\Dependency\Client\CustomerValidationPageToSessionClientBridge;
use SprykerShop\Yves\CustomerValidationPage\Dependency\Client\CustomerValidationPageToSessionClientInterface;
use Symfony\Cmf\Component\Routing\ChainRouterInterface;

class CustomerValidationPageDependencyProvider extends AbstractBundleDependencyProvider
{
    /**
     * @var string
     */
    public const CLIENT_CUSTOMER_STORAGE = 'CLIENT_CUSTOMER_STORAGE';

    /**
     * @var string
     */
    public const CLIENT_CUSTOMER = 'CLIENT_CUSTOMER';

    /**
     * @var string
     */
    public const CLIENT_SESSION = 'CLIENT_SESSION';

    /**
     * @uses \Spryker\Yves\Router\Plugin\Application\RouterApplicationPlugin::SERVICE_ROUTER
     *
     * @var string
     */
    public const SERVICE_ROUTER = 'routers';

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    public function provideDependencies(Container $container): Container
    {
        $container = $this->addCustomerStorageClient($container);
        $container = $this->addCustomerClient($container);
        $container = $this->addSessionClient($container);
        $container = $this->addRouter($container);

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addCustomerStorageClient(Container $container): Container
    {
        $container->set(static::CLIENT_CUSTOMER_STORAGE, function (Container $container): CustomerValidationPageToCustomerStorageClientInterface {
            return new CustomerValidationPageToCustomerStorageClientBridge(
                $container->getLocator()->customerStorage()->client(),
            );
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
        $container->set(static::CLIENT_CUSTOMER, function (Container $container): CustomerValidationPageToCustomerClientInterface {
            return new CustomerValidationPageToCustomerClientBridge(
                $container->getLocator()->customer()->client(),
            );
        });

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addSessionClient(Container $container): Container
    {
        $container->set(static::CLIENT_SESSION, function (Container $container): CustomerValidationPageToSessionClientInterface {
            return new CustomerValidationPageToSessionClientBridge(
                $container->getLocator()->session()->client(),
            );
        });

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addRouter(Container $container): Container
    {
        $container->set(static::SERVICE_ROUTER, function (ContainerInterface $container): ChainRouterInterface {
            return $container->getApplicationService(static::SERVICE_ROUTER);
        });

        return $container;
    }
}
