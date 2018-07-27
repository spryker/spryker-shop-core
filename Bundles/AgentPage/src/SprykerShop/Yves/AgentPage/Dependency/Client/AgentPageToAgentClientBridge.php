<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\AgentPage\Dependency\Client;

use Generated\Shared\Transfer\UserTransfer;
use Spryker\Client\Agent\AgentClientInterface;

class AgentPageToAgentClientBridge implements AgentPageToAgentClientInterface
{
    /**
     * @var \Spryker\Client\Agent\AgentClientInterface
     */
    protected $agentClient;

    /**
     * @param \Spryker\Client\Agent\AgentClientInterface $agentClient
     */
    public function __construct(AgentClientInterface $agentClient)
    {
        $this->agentClient = $agentClient;
    }

    /**
     * @param \Generated\Shared\Transfer\UserTransfer $userTransfer
     *
     * @return \Generated\Shared\Transfer\UserTransfer
     */
    public function getAgentByUsername(UserTransfer $userTransfer): UserTransfer
    {
        return $this->agentClient->getAgentByUsername($userTransfer);
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

    /**
     * @param \Generated\Shared\Transfer\UserTransfer $userTransfer
     *
     * @return void
     */
    public function setAgent(UserTransfer $userTransfer): void
    {
        $this->agentClient->setAgent($userTransfer);
    }
}
