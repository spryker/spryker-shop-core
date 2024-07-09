<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\AgentPage\Processor;

use Generated\Shared\Transfer\UserTransfer;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

class CurrentRequestProcessor implements CurrentRequestProcessorInterface
{
    /**
     * @var string
     */
    protected const RECORD_KEY_AGENT_USERNAME = 'agent_username';

    /**
     * @var string
     */
    protected const RECORD_KEY_AGENT_USER_UUID = 'agent_user_uuid';

    /**
     * @var string
     */
    protected const RECORD_KEY_EXTRA = 'extra';

    /**
     * @var string
     */
    protected const RECORD_KEY_REQUEST = 'request';

    /**
     * @var string
     */
    protected const SESSION_KEY_AGENT_SESSION = 'agent-session';

    /**
     * @var string
     */
    protected const SESSION_KEY_SECURITY_AGENT = '_security_agent';

    /**
     * @var \Symfony\Component\HttpFoundation\RequestStack
     */
    protected RequestStack $requestStack;

    /**
     * @param \Symfony\Component\HttpFoundation\RequestStack $requestStack
     */
    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }

    /**
     * @param array<string, mixed> $data
     *
     * @return array<string, mixed>
     */
    public function __invoke(array $data): array
    {
        $currentRequestData = $this->getCurrentRequestData();

        if (!$currentRequestData) {
            return $data;
        }

        return $this->setCurrentRequestData($data, $currentRequestData);
    }

    /**
     * @param array<string, mixed> $data
     * @param array<string, mixed> $currentRequestData
     *
     * @return array<string, mixed>
     */
    protected function setCurrentRequestData(array $data, array $currentRequestData): array
    {
        if (isset($data[static::RECORD_KEY_EXTRA][static::RECORD_KEY_REQUEST])) {
            $data[static::RECORD_KEY_EXTRA][static::RECORD_KEY_REQUEST] = array_merge(
                $data[static::RECORD_KEY_EXTRA][static::RECORD_KEY_REQUEST],
                $currentRequestData,
            );

            return $data;
        }

        $data[static::RECORD_KEY_EXTRA][static::RECORD_KEY_REQUEST] = $currentRequestData;

        return $data;
    }

    /**
     * @return array<string, mixed>
     */
    protected function getCurrentRequestData(): array
    {
        $currentRequest = $this->requestStack->getCurrentRequest();
        $currentRequestData = [];

        if (!$currentRequest || !$currentRequest->hasSession()) {
            return $currentRequestData;
        }

        $userTransfer = $this->findAgentUser($currentRequest);
        if ($userTransfer) {
            $currentRequestData[static::RECORD_KEY_AGENT_USERNAME] = $userTransfer->getUsername();
            $currentRequestData[static::RECORD_KEY_AGENT_USER_UUID] = $userTransfer->getUuid();
        }

        return $currentRequestData;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Generated\Shared\Transfer\UserTransfer|null
     */
    protected function findAgentUser(Request $request): ?UserTransfer
    {
        if (!$request->getSession()->has(static::SESSION_KEY_AGENT_SESSION)) {
            return null;
        }

        return $request->getSession()->get(static::SESSION_KEY_AGENT_SESSION);
    }
}
