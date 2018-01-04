<?php

/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ErrorPage;

use Spryker\Shared\Kernel\Communication\Application;
use Spryker\Yves\Kernel\AbstractFactory;

class ErrorPageFactory extends AbstractFactory
{

    /**
     * @return Application
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
