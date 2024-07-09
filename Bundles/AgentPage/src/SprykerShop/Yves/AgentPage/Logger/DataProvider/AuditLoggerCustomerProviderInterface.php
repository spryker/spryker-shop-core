<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\AgentPage\Logger\DataProvider;

use Generated\Shared\Transfer\CustomerTransfer;

interface AuditLoggerCustomerProviderInterface
{
    /**
     * @return \Generated\Shared\Transfer\CustomerTransfer|null
     */
    public function findOriginalCustomer(): ?CustomerTransfer;
}
