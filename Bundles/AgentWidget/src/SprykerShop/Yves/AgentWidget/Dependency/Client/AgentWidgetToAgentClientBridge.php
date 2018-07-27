<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\AgentWidget\Dependency\Client;

use Generated\Shared\Transfer\UserTransfer;

class AgentWidgetToAgentClientBridge implements AgentWidgetToAgentClientInterface
{
    /**
     * @var \Spryker\Client\Agent\AgentClientInterface
     */
    protected $agentClient;

    /**
     * @param \Spryker\Client\Agent\AgentClientInterface $agentClient
     */
    public function __construct($agentClient)
    {
        $this->agentClient = $agentClient;
    }

    /**
     * @return bool
     */
    public function isLoggedIn(): bool
    {
        return $this->agentClient->isLoggedIn();
    }

    /**
     * @return \Generated\Shared\Transfer\UserTransfer|null
     */
    public function getAgent(): ?UserTransfer
    {
        return $this->agentClient->getAgent();
    }
}
