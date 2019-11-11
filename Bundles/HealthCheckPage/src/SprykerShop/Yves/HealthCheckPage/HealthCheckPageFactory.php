<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\HealthCheckPage;

use Spryker\Yves\Kernel\AbstractFactory;
use SprykerShop\Yves\HealthCheckPage\Dependency\Service\HealthCheckPageToHealthCheckServiceInterface;

class HealthCheckPageFactory extends AbstractFactory
{
    /**
     * @return \SprykerShop\Yves\HealthCheckPage\Dependency\Service\HealthCheckPageToHealthCheckServiceInterface
     */
    public function getHealthCheckService(): HealthCheckPageToHealthCheckServiceInterface
    {
        return $this->getProvidedDependency(HealthCheckPageDependencyProvider::SERVICE_HEALTH_CHECK);
    }
}
