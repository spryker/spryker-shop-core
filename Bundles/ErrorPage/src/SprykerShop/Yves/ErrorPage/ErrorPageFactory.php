<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ErrorPage;

use Spryker\Yves\Kernel\AbstractFactory;
use Spryker\Yves\Router\Router\ChainRouter;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpKernel\HttpKernelInterface;

/**
 * @method \SprykerShop\Yves\ErrorPage\ErrorPageConfig getConfig()
 */
class ErrorPageFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Yves\Router\Router\ChainRouter
     */
    public function getRouter(): ChainRouter
    {
        return $this->getProvidedDependency(ErrorPageDependencyProvider::SERVICE_ROUTER);
    }

    /**
     * @return \Symfony\Component\HttpFoundation\RequestStack
     */
    public function getRequestStack(): RequestStack
    {
        return $this->getProvidedDependency(ErrorPageDependencyProvider::SERVICE_REQUEST_STACK);
    }

    /**
     * @return \Symfony\Component\HttpKernel\HttpKernelInterface
     */
    public function getKernel(): HttpKernelInterface
    {
        return $this->getProvidedDependency(ErrorPageDependencyProvider::SERVICE_KERNEL);
    }

    /**
     * @deprecated Will be removed without replacement.
     *
     * @return \Spryker\Shared\Kernel\Communication\Application
     */
    public function getApplication()
    {
        return $this->getProvidedDependency(ErrorPageDependencyProvider::PLUGIN_APPLICATION);
    }

    /**
     * @return \SprykerShop\Yves\ErrorPage\Dependency\Plugin\ExceptionHandlerPluginInterface[]
     */
    public function getExceptionHandlerPlugins()
    {
        return $this->getProvidedDependency(ErrorPageDependencyProvider::PLUGIN_EXCEPTION_HANDLERS);
    }
}
