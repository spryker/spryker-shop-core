<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\HeartbeatPage\Model\HealthIndicator;

use Exception;
use Spryker\Shared\Heartbeat\Code\HealthIndicatorInterface;
use SprykerShop\Yves\HeartbeatPage\Dependency\Client\HeartbeatPageToSessionClientInterface;

class SessionHealthIndicator extends AbstractHealthIndicator implements HealthIndicatorInterface
{
    const FAILURE_MESSAGE_UNABLE_TO_WRITE_SESSION = 'Unable to write session';
    const FAILURE_MESSAGE_UNABLE_TO_READ_SESSION = 'Unable to read session';
    const KEY_HEARTBEAT = 'heartbeat';

    /**
     * @var \SprykerShop\Yves\HeartbeatPage\Dependency\Client\HeartbeatPageToSessionClientInterface
     */
    protected $sessionClient;

    /**
     * @param \SprykerShop\Yves\HeartbeatPage\Dependency\Client\HeartbeatPageToSessionClientInterface $sessionClient
     */
    public function __construct(HeartbeatPageToSessionClientInterface $sessionClient)
    {
        $this->sessionClient = $sessionClient;
    }

    /**
     * @return void
     */
    public function doHealthCheck()
    {
        $this->checkWriteSession();
        $this->checkReadSession();
    }

    /**
     * @return void
     */
    private function checkWriteSession()
    {
        try {
            $this->sessionClient->set(self::KEY_HEARTBEAT, 'ok');
        } catch (Exception $e) {
            $this->addFailure(self::FAILURE_MESSAGE_UNABLE_TO_WRITE_SESSION);
            $this->addFailure($e->getMessage());
        }
    }

    /**
     * @return void
     */
    private function checkReadSession()
    {
        try {
            $this->sessionClient->get(self::KEY_HEARTBEAT);
        } catch (Exception $e) {
            $this->addFailure(self::FAILURE_MESSAGE_UNABLE_TO_READ_SESSION);
            $this->addFailure($e->getMessage());
        }
    }
}
