<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\AgentPage\Logger;

use Generated\Shared\Transfer\AuditLoggerConfigCriteriaTransfer;
use Spryker\Shared\Log\AuditLoggerTrait;
use SprykerShop\Yves\AgentPage\Logger\DataProvider\AuditLoggerCustomerProviderInterface;

class AuditLogger implements AuditLoggerInterface
{
    use AuditLoggerTrait;

    /**
     * @uses \Spryker\Shared\Log\LogConfig::AUDIT_LOGGER_CHANNEL_NAME_SECURITY
     *
     * @var string
     */
    protected const AUDIT_LOGGER_CHANNEL_NAME_SECURITY = 'security';

    /**
     * @uses \Spryker\Shared\Log\Handler\TagFilterBufferedStreamHandler::RECORD_KEY_CONTEXT_TAGS
     *
     * @var string
     */
    protected const AUDIT_LOGGER_RECORD_KEY_CONTEXT_TAGS = 'tags';

    /**
     * @var \SprykerShop\Yves\AgentPage\Logger\DataProvider\AuditLoggerCustomerProviderInterface
     */
    protected AuditLoggerCustomerProviderInterface $auditLoggerCustomerProvider;

    /**
     * @param \SprykerShop\Yves\AgentPage\Logger\DataProvider\AuditLoggerCustomerProviderInterface $auditLoggerCustomerProvider
     */
    public function __construct(AuditLoggerCustomerProviderInterface $auditLoggerCustomerProvider)
    {
        $this->auditLoggerCustomerProvider = $auditLoggerCustomerProvider;
    }

    /**
     * @return void
     */
    public function addAgentFailedLoginAuditLog(): void
    {
        $this->addAuditLogWithTags('Failed Login (Agent)', ['agent_failed_login']);
    }

    /**
     * @return void
     */
    public function addAgentSuccessfulLoginAuditLog(): void
    {
        $this->addAuditLogWithTags('Successful Login (Agent)', ['agent_successful_login']);
    }

    /**
     * @return void
     */
    public function addImpersonationStartedAuditLog(): void
    {
        $this->addAuditLogWithTags('Impersonation Started', ['impersonation_started']);
    }

    /**
     * @return void
     */
    public function addImpersonationEndedAuditLog(): void
    {
        $context = $this->addOriginalCustomerContext([static::AUDIT_LOGGER_RECORD_KEY_CONTEXT_TAGS => ['impersonation_ended']]);

        $this->addAuditLog('Impersonation Ended', $context);
    }

    /**
     * @param string $action
     * @param list<string> $tags
     *
     * @return void
     */
    protected function addAuditLogWithTags(string $action, array $tags): void
    {
        $this->addAuditLog($action, [static::AUDIT_LOGGER_RECORD_KEY_CONTEXT_TAGS => $tags]);
    }

    /**
     * @param string $action
     * @param array<string, mixed> $context
     *
     * @return void
     */
    protected function addAuditLog(string $action, array $context): void
    {
        $this->getAuditLogger(
            (new AuditLoggerConfigCriteriaTransfer())->setChannelName(static::AUDIT_LOGGER_CHANNEL_NAME_SECURITY),
        )->info($action, $context);
    }

    /**
     * @param array<string, mixed> $context
     *
     * @return array<string, mixed>
     */
    protected function addOriginalCustomerContext(array $context): array
    {
        $customerTransfer = $this->auditLoggerCustomerProvider->findOriginalCustomer();
        if ($customerTransfer) {
            $context['original_username'] = $customerTransfer->getEmail();
            $context['original_customer_reference'] = $customerTransfer->getCustomerReference();
        }

        return $context;
    }
}
