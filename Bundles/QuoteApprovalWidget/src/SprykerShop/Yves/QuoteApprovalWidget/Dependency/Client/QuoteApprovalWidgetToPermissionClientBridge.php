<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\QuoteApprovalWidget\Dependency\Client;

use Generated\Shared\Transfer\PermissionTransfer;

class QuoteApprovalWidgetToPermissionClientBridge implements QuoteApprovalWidgetToPermissionClientInterface
{
    /**
     * @var \Spryker\Client\Permission\PermissionClientInterface
     */
    protected $permissionClient;

    /**
     * @param \Spryker\Client\Permission\PermissionClientInterface $permissionClient
     */
    public function __construct($permissionClient)
    {
        $this->permissionClient = $permissionClient;
    }

    /**
     * @param string $permissionKey
     *
     * @return \Generated\Shared\Transfer\PermissionTransfer|null
     */
    public function findCustomerPermissionByKey(string $permissionKey): ?PermissionTransfer
    {
        return $this->permissionClient->findCustomerPermissionByKey($permissionKey);
    }
}
