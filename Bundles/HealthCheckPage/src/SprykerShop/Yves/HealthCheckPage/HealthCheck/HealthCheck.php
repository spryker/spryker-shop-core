<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\HealthCheckPage\HealthCheck;

use Generated\Shared\Transfer\HealthCheckRequestTransfer;
use Generated\Shared\Transfer\HealthCheckResponseTransfer;
use SprykerShop\Yves\HealthCheckPage\Dependency\Client\HealthCheckPageToHealthCheckClientInterface;
use SprykerShop\Yves\HealthCheckPage\HealthCheckPageConfig;

class HealthCheck implements HealthCheckInterface
{
    /**
     * @var \SprykerShop\Yves\HealthCheckPage\Dependency\Client\HealthCheckPageToHealthCheckClientInterface
     */
    protected $healthCheckClient;

    /**
     * @var \SprykerShop\Yves\HealthCheckPage\HealthCheckPageConfig
     */
    protected $healthCheckPageConfig;

    /**
     * @param \SprykerShop\Yves\HealthCheckPage\Dependency\Client\HealthCheckPageToHealthCheckClientInterface $healthCheckClient
     * @param \SprykerShop\Yves\HealthCheckPage\HealthCheckPageConfig $healthCheckPageConfig
     */
    public function __construct(HealthCheckPageToHealthCheckClientInterface $healthCheckClient, HealthCheckPageConfig $healthCheckPageConfig)
    {
        $this->healthCheckClient = $healthCheckClient;
        $this->healthCheckPageConfig = $healthCheckPageConfig;
    }

    /**
     * @param string|null $requestedServices
     *
     * @return \Generated\Shared\Transfer\HealthCheckResponseTransfer
     */
    public function executeHealthCheck(?string $requestedServices = null): HealthCheckResponseTransfer
    {
        $healthCheckRequestTransfer = (new HealthCheckRequestTransfer())
            ->setAvailableServices($this->healthCheckPageConfig->getAvailableHealthCheckServices());

        if ($requestedServices !== null) {
            $healthCheckRequestTransfer->setRequestedServices(explode(',', $requestedServices));
        }

        $healthCheckRequestTransfer = $this->healthCheckClient->executeHealthCheck($healthCheckRequestTransfer);

        return $healthCheckRequestTransfer;
    }
}
