<?php

/**
 * This file is part of the Spryker Demoshop.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerShop\Client\CustomerPage\Zed;

use Generated\Shared\Transfer\CustomerOverviewRequestTransfer;
use Spryker\Client\Customer\Zed\CustomerStubInterface as SprykerCustomerStubInterface;

interface CustomerPageStubInterface extends SprykerCustomerStubInterface
{
    /**
     * @param \Generated\Shared\Transfer\CustomerOverviewRequestTransfer $overviewRequest
     *
     * @return \Generated\Shared\Transfer\CustomerOverviewResponseTransfer
     */
    public function getCustomerOverview(CustomerOverviewRequestTransfer $overviewRequest);
}
