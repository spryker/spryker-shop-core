<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ErrorPage;

use Spryker\Yves\Kernel\AbstractFactory;
use SprykerShop\Yves\ErrorPage\Message\ErrorMessage;
use SprykerShop\Yves\ErrorPage\Message\ErrorMessageInterface;

/**
 * @method \SprykerShop\Yves\ErrorPage\ErrorPageConfig getConfig()
 */
class ErrorPageFactory extends AbstractFactory
{
    /**
     * @return \SprykerShop\Yves\ErrorPage\Message\ErrorMessageInterface
     */
    public function createErrorMessage(): ErrorMessageInterface
    {
        return new ErrorMessage(
            $this->getConfig()
        );
    }

    /**
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
