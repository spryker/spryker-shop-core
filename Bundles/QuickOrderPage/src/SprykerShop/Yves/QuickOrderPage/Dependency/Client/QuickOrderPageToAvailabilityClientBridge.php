<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\QuickOrderPage\Dependency\Client;

use Generated\Shared\Transfer\ProductConcreteAvailabilityRequestTransfer;

class QuickOrderPageToAvailabilityClientBridge implements QuickOrderPageToAvailabilityClientInterface
{
    /**
     * @var \Spryker\Client\Availability\AvailabilityClientInterface
     */
    protected $availabilityClient;

    /**
     * @param \Spryker\Client\Availability\AvailabilityClientInterface $availabilityClient
     */
    public function __construct($availabilityClient)
    {
        $this->availabilityClient = $availabilityClient;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteAvailabilityRequestTransfer $productConcreteAvailabilityRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConcreteAvailabilityTransfer|null
     */
    public function findProductConcreteAvailability(ProductConcreteAvailabilityRequestTransfer $productConcreteAvailabilityRequestTransfer)
    {
        return $this->availabilityClient->findProductConcreteAvailability($productConcreteAvailabilityRequestTransfer);
    }
}
