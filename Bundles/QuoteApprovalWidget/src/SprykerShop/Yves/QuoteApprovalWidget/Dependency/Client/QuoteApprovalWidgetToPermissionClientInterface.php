<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\QuoteApprovalWidget\Dependency\Client;

use Generated\Shared\Transfer\PermissionTransfer;

interface QuoteApprovalWidgetToPermissionClientInterface
{
    /**
     * @param string $permissionKey
     *
     * @return \Generated\Shared\Transfer\PermissionTransfer|null
     */
    public function findCustomerPermissionByKey(string $permissionKey): ?PermissionTransfer;
}
