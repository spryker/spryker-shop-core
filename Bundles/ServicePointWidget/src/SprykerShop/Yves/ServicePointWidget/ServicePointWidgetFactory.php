<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ServicePointWidget;

use Spryker\Yves\Kernel\AbstractFactory;
use SprykerShop\Yves\ServicePointWidget\Dependency\Client\ServicePointWidgetToServicePointSearchClientInterface;
use SprykerShop\Yves\ServicePointWidget\Reader\ServicePointReader;
use SprykerShop\Yves\ServicePointWidget\Reader\ServicePointReaderInterface;
use Twig\Environment;

class ServicePointWidgetFactory extends AbstractFactory
{
    /**
     * @return \SprykerShop\Yves\ServicePointWidget\Reader\ServicePointReaderInterface
     */
    public function createServicePointReader(): ServicePointReaderInterface
    {
        return new ServicePointReader(
            $this->getServicePointSearchClient(),
            $this->getTwigEnvironment(),
        );
    }

    /**
     * @return \SprykerShop\Yves\ServicePointWidget\Dependency\Client\ServicePointWidgetToServicePointSearchClientInterface
     */
    public function getServicePointSearchClient(): ServicePointWidgetToServicePointSearchClientInterface
    {
        return $this->getProvidedDependency(ServicePointWidgetDependencyProvider::CLIENT_SERVICE_POINT_SEARCH);
    }

    /**
     * @return \Twig\Environment
     */
    public function getTwigEnvironment(): Environment
    {
        return $this->getProvidedDependency(ServicePointWidgetDependencyProvider::TWIG_ENVIRONMENT);
    }
}
