<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\HeartbeatPage\Model\HealthIndicator;

use Exception;
use Spryker\Shared\Heartbeat\Code\HealthIndicatorInterface;
use SprykerShop\Yves\HeartbeatPage\Dependency\Client\HeartbeatPageToSearchClientInterface;

class SearchHealthIndicator extends AbstractHealthIndicator implements HealthIndicatorInterface
{
    public const FAILURE_MESSAGE_UNABLE_TO_CONNECT_TO_SEARCH = 'Unable to connect to search';

    /**
     * @var \SprykerShop\Yves\HeartbeatPage\Dependency\Client\HeartbeatPageToSearchClientInterface
     */
    protected $searchClient;

    /**
     * @param \SprykerShop\Yves\HeartbeatPage\Dependency\Client\HeartbeatPageToSearchClientInterface $searchClient
     */
    public function __construct(HeartbeatPageToSearchClientInterface $searchClient)
    {
        $this->searchClient = $searchClient;
    }

    /**
     * @return void
     */
    public function doHealthCheck()
    {
        $this->checkConnectToSearch();
    }

    /**
     * @return void
     */
    private function checkConnectToSearch()
    {
        try {
            $this->searchClient->checkConnection();
        } catch (Exception $e) {
            $this->addFailure(self::FAILURE_MESSAGE_UNABLE_TO_CONNECT_TO_SEARCH);
            $this->addFailure($e->getMessage());
        }
    }
}
