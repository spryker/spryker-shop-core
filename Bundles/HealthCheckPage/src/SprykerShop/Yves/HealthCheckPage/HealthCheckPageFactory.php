<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\HealthCheckPage;

use Spryker\Yves\Kernel\AbstractFactory;
use SprykerShop\Yves\HealthCheckPage\Dependency\Client\HealthCheckPageToHealthCheckClientInterface;
use SprykerShop\Yves\HealthCheckPage\HealthCheck\HealthChecker;
use SprykerShop\Yves\HealthCheckPage\HealthCheck\HealthCheckerInterface;

/**
 * @method \SprykerShop\Yves\HealthCheckPage\HealthCheckPageConfig getConfig()
 */
class HealthCheckPageFactory extends AbstractFactory
{
    /**
     * @return \SprykerShop\Yves\HealthCheckPage\HealthCheck\HealthCheckerInterface
     */
    public function createHealthChecker(): HealthCheckerInterface
    {
        return new HealthChecker(
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
