<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\HealthCheckPage;

use Spryker\Yves\Kernel\AbstractFactory;
use SprykerShop\Yves\HealthCheckPage\Dependency\Client\HealthCheckPageToHealthCheckClientInterface;
use SprykerShop\Yves\HealthCheckPage\HealthCheck\HealthCheck;
use SprykerShop\Yves\HealthCheckPage\HealthCheck\HealthCheckInterface;

/**
 * @method \SprykerShop\Yves\HealthCheckPage\HealthCheckPageConfig getConfig()
 */
class HealthCheckPageFactory extends AbstractFactory
{
    /**
     * @return \SprykerShop\Yves\HealthCheckPage\HealthCheck\HealthCheckInterface
     */
    public function createHealthChecker(): HealthCheckInterface
    {
        return new HealthCheck(
            $this->getHealthCheckClient(),
            $this->getConfig()
        );
    }

    /**
     * @return \SprykerShop\Yves\HealthCheckPage\Dependency\Client\HealthCheckPageToHealthCheckClientInterface
     */
    public function getHealthCheckClient(): HealthCheckPageToHealthCheckClientInterface
    {
        return $this->getProvidedDependency(HealthCheckPageDependencyProvider::CLIENT_HEALTH_CHECK);
    }
}
