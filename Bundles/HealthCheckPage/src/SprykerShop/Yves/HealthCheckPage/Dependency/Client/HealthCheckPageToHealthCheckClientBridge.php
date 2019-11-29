<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\HealthCheckPage\Dependency\Client;

use Generated\Shared\Transfer\HealthCheckRequestTransfer;
use Generated\Shared\Transfer\HealthCheckResponseTransfer;

class HealthCheckPageToHealthCheckClientBridge implements HealthCheckPageToHealthCheckClientInterface
{
    /**
     * @var \Spryker\Client\HealthCheck\HealthCheckClientInterface
     */
    protected $healthCheckClient;

    /**
     * @param \Spryker\Client\HealthCheck\HealthCheckClientInterface $healthCheckClient
     */
    public function __construct($healthCheckClient)
    {
        $this->healthCheckClient = $healthCheckClient;
    }

    /**
     * @param \Generated\Shared\Transfer\HealthCheckRequestTransfer $healthCheckRequestTransfer
     *
     * @return \Generated\Shared\Transfer\HealthCheckResponseTransfer
     */
    public function executeHealthCheck(HealthCheckRequestTransfer $healthCheckRequestTransfer): HealthCheckResponseTransfer
    {
        return $this->healthCheckClient->executeHealthCheck($healthCheckRequestTransfer);
    }
}
