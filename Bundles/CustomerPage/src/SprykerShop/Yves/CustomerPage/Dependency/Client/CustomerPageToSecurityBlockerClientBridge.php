<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CustomerPage\Dependency\Client;

use Generated\Shared\Transfer\SecurityCheckAuthContextTransfer;
use Generated\Shared\Transfer\SecurityCheckAuthResponseTransfer;

class CustomerPageToSecurityBlockerClientBridge implements CustomerPageToSecurityBlockerClientInterface
{
    /**
     * @var \Spryker\Client\SecurityBlocker\SecurityBlockerClientInterface
     */
    protected $securityBlockerClient;

    /**
     * @param \Spryker\Client\SecurityBlocker\SecurityBlockerClientInterface $securityBlockerClient
     */
    public function __construct($securityBlockerClient)
    {
        $this->securityBlockerClient = $securityBlockerClient;
    }

    /**
     * @inheritDoc
     */
    public function incrementLoginAttemptCount(SecurityCheckAuthContextTransfer $securityCheckAuthContextTransfer): SecurityCheckAuthResponseTransfer
    {
        return $this->securityBlockerClient->incrementLoginAttemptCount($securityCheckAuthContextTransfer);
    }

    /**
     * @inheritDoc
     */
    public function isAccountBlocked(SecurityCheckAuthContextTransfer $securityCheckAuthContextTransfer): SecurityCheckAuthResponseTransfer
    {
        return $this->securityBlockerClient->isAccountBlocked($securityCheckAuthContextTransfer);
    }
}
