<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\SecurityBlockerPage\Builder;

use Generated\Shared\Transfer\SecurityCheckAuthResponseTransfer;
use SprykerShop\Yves\SecurityBlockerPage\Dependency\Client\SecurityBlockerPageToGlossaryStorageClientInterface;

class MessageBuilder implements MessageBuilderInterface
{
    /**
     * @var string
     */
    protected const GLOSSARY_KEY_ERROR_ACCOUNT_BLOCKED = 'security_blocker_page.error.account_blocked';

    /**
     * @var \SprykerShop\Yves\SecurityBlockerPage\Dependency\Client\SecurityBlockerPageToGlossaryStorageClientInterface
     */
    protected $glossaryStorageClient;

    /**
     * @param \SprykerShop\Yves\SecurityBlockerPage\Dependency\Client\SecurityBlockerPageToGlossaryStorageClientInterface $glossaryStorageClient
     */
    public function __construct(SecurityBlockerPageToGlossaryStorageClientInterface $glossaryStorageClient)
    {
        $this->glossaryStorageClient = $glossaryStorageClient;
    }

    /**
     * @param \Generated\Shared\Transfer\SecurityCheckAuthResponseTransfer $securityCheckAuthResponseTransfer
     * @param string $localeName
     *
     * @return string
     */
    public function getExceptionMessage(
        SecurityCheckAuthResponseTransfer $securityCheckAuthResponseTransfer,
        string $localeName
    ): string {
        return $this->glossaryStorageClient->translate(
            static::GLOSSARY_KEY_ERROR_ACCOUNT_BLOCKED,
            $localeName,
            ['%minutes%' => $this->convertSecondsToReadableTime($securityCheckAuthResponseTransfer)],
        );
    }

    /**
     * @param \Generated\Shared\Transfer\SecurityCheckAuthResponseTransfer $securityCheckAuthResponseTransfer
     *
     * @return string
     */
    protected function convertSecondsToReadableTime(
        SecurityCheckAuthResponseTransfer $securityCheckAuthResponseTransfer
    ): string {
        $seconds = $securityCheckAuthResponseTransfer->getBlockedFor() ?? 0;

        return (string)ceil($seconds / 60);
    }
}
