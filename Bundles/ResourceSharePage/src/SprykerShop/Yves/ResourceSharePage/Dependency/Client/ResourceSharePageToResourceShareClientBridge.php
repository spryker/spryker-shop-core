<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ResourceSharePage\Dependency\Client;

use Generated\Shared\Transfer\ResourceShareRequestTransfer;
use Generated\Shared\Transfer\ResourceShareResponseTransfer;

class ResourceSharePageToResourceShareClientBridge implements ResourceSharePageToResourceShareClientInterface
{
    /**
     * @var \Spryker\Client\ResourceShare\ResourceShareClientInterface
     */
    protected $resourceShareClient;

    /**
     * @param \Spryker\Client\ResourceShare\ResourceShareClientInterface $resourceShareClient
     */
    public function __construct($resourceShareClient)
    {
        $this->resourceShareClient = $resourceShareClient;
    }

    /**
     * @param \Generated\Shared\Transfer\ResourceShareRequestTransfer $resourceShareRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ResourceShareResponseTransfer
     */
    public function activateResourceShare(ResourceShareRequestTransfer $resourceShareRequestTransfer): ResourceShareResponseTransfer
    {
        return $this->resourceShareClient->activateResourceShare($resourceShareRequestTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ResourceShareRequestTransfer $resourceShareRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ResourceShareResponseTransfer
     */
    public function getResourceShareByUuid(ResourceShareRequestTransfer $resourceShareRequestTransfer): ResourceShareResponseTransfer
    {
        return $this->resourceShareClient->getResourceShareByUuid($resourceShareRequestTransfer);
    }
}
