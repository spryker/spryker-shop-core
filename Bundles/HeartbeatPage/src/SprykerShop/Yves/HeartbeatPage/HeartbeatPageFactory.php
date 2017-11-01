<?php

/**
 * This file is part of the Spryker Demoshop.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerShop\Yves\HeartbeatPage;

use SprykerShop\Yves\HeartbeatPage\Model\HealthChecker;
use SprykerShop\Yves\HeartbeatPage\Model\HealthIndicator\SearchHealthIndicator;
use SprykerShop\Yves\HeartbeatPage\Model\HealthIndicator\SessionHealthIndicator;
use SprykerShop\Yves\HeartbeatPage\Model\HealthIndicator\StorageHealthIndicator;
use Spryker\Yves\Kernel\AbstractFactory;

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
    protected function createSearchHealthIndicator()
    {
        return new SearchHealthIndicator($this->getSearchClient());
    }

    /**
     * @return \Spryker\Client\Search\SearchClientInterface
     */
    protected function getSearchClient()
    {
        return $this->getProvidedDependency(HeartbeatPageDependencyProvider::CLIENT_SEARCH);
    }

    /**
     * @return \SprykerShop\Yves\HeartbeatPage\Model\HealthIndicator\SessionHealthIndicator
     */
    protected function createSessionHealthIndicator()
    {
        return new SessionHealthIndicator($this->getSessionClient());
    }

    /**
     * @return \Spryker\Client\Session\SessionClientInterface
     */
    protected function getSessionClient()
    {
        return $this->getProvidedDependency(HeartbeatPageDependencyProvider::CLIENT_SESSION);
    }

    /**
     * @return \SprykerShop\Yves\HeartbeatPage\Model\HealthIndicator\StorageHealthIndicator
     */
    protected function createStorageHealthIndicator()
    {
        return new StorageHealthIndicator($this->getStorageClient());
    }

    /**
     * @return \Spryker\Client\Storage\StorageClientInterface
     */
    protected function getStorageClient()
    {
        return $this->getProvidedDependency(HeartbeatPageDependencyProvider::CLIENT_STORAGE);
    }
}
