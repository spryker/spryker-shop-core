<?php

/**
 * This file is part of the Spryker Demoshop.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerShop\Client\CustomerPage;

use Generated\Shared\Transfer\CustomerOverviewRequestTransfer;
use Spryker\Client\Kernel\AbstractClient;

/**
 * @method \SprykerShop\Client\CustomerPage\CustomerPageFactory getFactory()
 */
class CustomerPageClient extends AbstractClient implements CustomerPageClientInterface
{
    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CustomerOverviewRequestTransfer $overviewRequest
     *
     * @return \Generated\Shared\Transfer\CustomerOverviewResponseTransfer
     */
    public function getCustomerOverview(CustomerOverviewRequestTransfer $overviewRequest)
    {
        return $this->getFactory()
            ->createZedCustomerStub()
            ->getCustomerOverview($overviewRequest);
    }
}
