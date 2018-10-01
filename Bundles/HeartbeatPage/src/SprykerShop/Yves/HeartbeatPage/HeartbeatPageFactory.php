<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\HeartbeatPage;

use Spryker\Yves\Kernel\AbstractFactory;
use SprykerShop\Yves\HeartbeatPage\Dependency\Client\HeartbeatPageToSearchClientInterface;
use SprykerShop\Yves\HeartbeatPage\Dependency\Client\HeartbeatPageToSessionClientInterface;
use SprykerShop\Yves\HeartbeatPage\Dependency\Client\HeartbeatPageToStorageClientInterface;
use SprykerShop\Yves\HeartbeatPage\Model\HealthChecker;
use SprykerShop\Yves\HeartbeatPage\Model\HealthIndicator\SearchHealthIndicator;
use SprykerShop\Yves\HeartbeatPage\Model\HealthIndicator\SessionHealthIndicator;
use SprykerShop\Yves\HeartbeatPage\Model\HealthIndicator\StorageHealthIndicator;

class HeartbeatPageFactory extends AbstractFactory
{
    /**
     * @return \SprykerShop\Yves\HeartbeatPage\Model\HealthChecker
     */
    public function createHealthChecker()
    {
        $healthChecker = new HealthChecker();
        $healthChecker->setHealthIndicator([
            $this->createSearchHealthIndicator(),
            $this->createSessionHealthIndicator(),
            $this->createStorageHealthIndicator(),
        ]);

        return $healthChecker;
    }

    /**
     * @return \SprykerShop\Yves\HeartbeatPage\Model\HealthIndicator\SearchHealthIndicator
     */
    public function createSearchHealthIndicator()
    {
        return new SearchHealthIndicator($this->getSearchClient());
    }

    /**
     * @return \SprykerShop\Yves\HeartbeatPage\Dependency\Client\HeartbeatPageToSearchClientInterface
     */
    public function getSearchClient(): HeartbeatPageToSearchClientInterface
    {
        return $this->getProvidedDependency(HeartbeatPageDependencyProvider::CLIENT_SEARCH);
    }

    /**
     * @return \SprykerShop\Yves\HeartbeatPage\Model\HealthIndicator\SessionHealthIndicator
     */
    public function createSessionHealthIndicator()
    {
        return new SessionHealthIndicator($this->getSessionClient());
    }

    /**
     * @return \SprykerShop\Yves\HeartbeatPage\Dependency\Client\HeartbeatPageToSessionClientInterface
     */
    public function getSessionClient(): HeartbeatPageToSessionClientInterface
    {
        return $this->getProvidedDependency(HeartbeatPageDependencyProvider::CLIENT_SESSION);
    }

    /**
     * @return \SprykerShop\Yves\HeartbeatPage\Model\HealthIndicator\StorageHealthIndicator
     */
    public function createStorageHealthIndicator()
    {
        return new StorageHealthIndicator($this->getStorageClient());
    }

    /**
     * @return \SprykerShop\Yves\HeartbeatPage\Dependency\Client\HeartbeatPageToStorageClientInterface
     */
    public function getStorageClient(): HeartbeatPageToStorageClientInterface
    {
        return $this->getProvidedDependency(HeartbeatPageDependencyProvider::CLIENT_STORAGE);
    }
}
