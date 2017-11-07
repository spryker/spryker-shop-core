<?php

/**
 * This file is part of the Spryker Demoshop.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerShop\Client\CustomerPage;

use Generated\Shared\Transfer\CustomerOverviewRequestTransfer;

interface CustomerPageClientInterface
{
    /**
     * Specification:
     * - Loads information about e.g. orders and newsletter subscriptions.
     * - Returns a CustomerOverviewResponseTransfer.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CustomerOverviewRequestTransfer $overviewRequest
     *
     * @return \Generated\Shared\Transfer\CustomerOverviewResponseTransfer
     */
    public function getCustomerOverview(CustomerOverviewRequestTransfer $overviewRequest);
}
