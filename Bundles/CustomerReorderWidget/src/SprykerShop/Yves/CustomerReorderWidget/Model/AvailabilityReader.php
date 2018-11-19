<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CustomerReorderWidget\Model;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\SpyAvailabilityAbstractEntityTransfer;
use SprykerShop\Yves\CustomerReorderWidget\Dependency\Client\CustomerReorderWidgetToAvailabilityStorageClientInterface;

class AvailabilityReader implements AvailabilityReaderInterface
{
    /**
     * @var \SprykerShop\Yves\CustomerReorderWidget\Dependency\Client\CustomerReorderWidgetToAvailabilityStorageClientInterface
     */
    protected $availabilityStorageClient;

    /**
     * @param \SprykerShop\Yves\CustomerReorderWidget\Dependency\Client\CustomerReorderWidgetToAvailabilityStorageClientInterface $availabilityStorageClient
     */
    public function __construct(CustomerReorderWidgetToAvailabilityStorageClientInterface $availabilityStorageClient)
    {
        $this->availabilityStorageClient = $availabilityStorageClient;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return \Generated\Shared\Transfer\SpyAvailabilityAbstractEntityTransfer
     */
    public function getAvailabilityAbstractByItemTransfer(ItemTransfer $itemTransfer): SpyAvailabilityAbstractEntityTransfer
    {
        $itemTransfer->requireIdProductAbstract();

        return $this->availabilityStorageClient->getAvailabilityAbstract($itemTransfer->getIdProductAbstract());
    }
}
