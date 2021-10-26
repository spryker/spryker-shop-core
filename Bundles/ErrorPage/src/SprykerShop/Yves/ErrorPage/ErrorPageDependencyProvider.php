<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ErrorPage;

use Spryker\Shared\Kernel\ContainerInterface;
use Spryker\Yves\Kernel\AbstractBundleDependencyProvider;
use Spryker\Yves\Kernel\Container;
use Spryker\Yves\Kernel\Plugin\Pimple;

/**
 * @method \SprykerShop\Yves\ErrorPage\ErrorPageConfig getConfig()
 */
class ErrorPageDependencyProvider extends AbstractBundleDependencyProvider
{
    /**
     * @deprecated Will be removed without replacement.
     *
     * @var string
     */
    public const PLUGIN_APPLICATION = 'PLUGIN_APPLICATION';

    /**
     * @var string
     */
    public const PLUGIN_EXCEPTION_HANDLERS = 'PLUGIN_EXCEPTION_HANDLERS';

    /**
     * @uses \Spryker\Yves\Router\Plugin\Application\RouterApplicationPlugin::SERVICE_ROUTER
     *
     * @var string
     */
    public const SERVICE_ROUTER = 'routers';

    /**
     * @uses \Spryker\Yves\Http\Plugin\Application\HttpApplicationPlugin::SERVICE_REQUEST_STACK
     *
     * @var string
     */
    public const SERVICE_REQUEST_STACK = 'request_stack';

    /**
     * @uses \Spryker\Yves\Http\Plugin\Application\HttpApplicationPlugin::SERVICE_KERNEL
     *
     * @var string
     */
    public const SERVICE_KERNEL = 'kernel';

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    public function provideDependencies(Container $container)
    {
        $container = $this->addRouter($container);
        $container = $this->addRequestStack($container);
        $container = $this->addKernel($container);
        $container = $this->addApplicationPlugin($container);
        $container = $this->createExceptionHandlerPlugins($container);

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addRouter(Container $container)
    {
        $container->set(static::SERVICE_ROUTER, function (ContainerInterface $container) {
            return $container->getApplicationService(static::SERVICE_ROUTER);
        });

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addRequestStack(Container $container)
    {
        $container->set(static::SERVICE_REQUEST_STACK, function (ContainerInterface $container) {
            return $container->getApplicationService(static::SERVICE_REQUEST_STACK);
        });

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addKernel(Container $container)
    {
        $container->set(static::SERVICE_KERNEL, function (ContainerInterface $container) {
            return $container->getApplicationService(static::SERVICE_KERNEL);
        });

        return $container;
    }

    /**
     * @deprecated Will be removed without replacement.
     *
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addApplicationPlugin(Container $container)
    {
        $container->set(static::PLUGIN_APPLICATION, function () {
            $pimplePlugin = new Pimple();

            return $pimplePlugin->getApplication();
        });

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function createExceptionHandlerPlugins(Container $container)
    {
        $container->set(static::PLUGIN_EXCEPTION_HANDLERS, function () {
            return $this->getExceptionHandlerPlugins();
        });

        return $container;
    }

    /**
     * @return array<\SprykerShop\Yves\ErrorPageExtension\Dependency\Plugin\ExceptionHandlerPluginInterface>
     */
    protected function getExceptionHandlerPlugins()
    {
        return [];
    }
}
