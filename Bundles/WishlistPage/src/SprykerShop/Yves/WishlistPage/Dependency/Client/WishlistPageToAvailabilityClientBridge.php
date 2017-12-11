<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\WishlistPage\Dependency\Client;

class WishlistPageToAvailabilityClientBridge implements WishlistPageToAvailabilityClientInterface
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
     * @param int $idProductAbstract
     *
     * @return \Generated\Shared\Transfer\StorageAvailabilityTransfer
     */
    public function getProductAvailabilityByIdProductAbstract($idProductAbstract)
    {
        return $this->availabilityClient->getProductAvailabilityByIdProductAbstract($idProductAbstract);
    }
}
