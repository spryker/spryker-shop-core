<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CustomerPage\Logger;

use Generated\Shared\Transfer\AuditLoggerConfigCriteriaTransfer;
use Generated\Shared\Transfer\CustomerResponseTransfer;
use Spryker\Shared\Log\AuditLoggerTrait;

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
     * @return void
     */
    public function addFailedLoginAuditLog(): void
    {
        $this->addAuditLogWithTags('Failed Login', ['failed_login']);
    }

    /**
     * @return void
     */
    public function addSuccessfulLoginAuditLog(): void
    {
        $this->addAuditLogWithTags('Successful Login', ['successful_login']);
    }

    /**
     * @return void
     */
    public function addPasswordResetRequestedAuditLog(): void
    {
        $this->addAuditLogWithTags('Password Reset Requested', ['password_reset_requested']);
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerResponseTransfer $customerResponseTransfer
     *
     * @return void
     */
    public function addPasswordUpdatedAfterResetAuditLog(CustomerResponseTransfer $customerResponseTransfer): void
    {
        $context = $this->addCustomerContext(
            $customerResponseTransfer,
            [static::AUDIT_LOGGER_RECORD_KEY_CONTEXT_TAGS => ['password_updated_after_reset']],
        );

        $this->addAuditLog('Password Updated after Reset', $context);
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerResponseTransfer $customerResponseTransfer
     * @param array<string, mixed> $context
     *
     * @return array<string, mixed>
     */
    protected function addCustomerContext(CustomerResponseTransfer $customerResponseTransfer, array $context): array
    {
        if ($customerResponseTransfer->getCustomerTransfer()) {
            $context['username'] = $customerResponseTransfer->getCustomerTransfer()->getEmail();
            $context['customer_reference'] = $customerResponseTransfer->getCustomerTransfer()->getCustomerReference();
        }

        return $context;
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
}
