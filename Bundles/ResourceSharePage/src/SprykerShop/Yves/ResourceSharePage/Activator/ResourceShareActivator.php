<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ResourceSharePage\Activator;

use Generated\Shared\Transfer\ResourceShareRequestTransfer;
use Generated\Shared\Transfer\ResourceShareResponseTransfer;
use SprykerShop\Yves\ResourceSharePage\Dependency\Client\ResourceSharePageToResourceShareClientInterface;

class ResourceShareActivator implements ResourceShareActivatorInterface
{
    /**
     * @var \SprykerShop\Yves\ResourceSharePage\Dependency\Client\ResourceSharePageToResourceShareClientInterface
     */
    protected $resourceShareClient;

    /**
     * @param \SprykerShop\Yves\ResourceSharePage\Dependency\Client\ResourceSharePageToResourceShareClientInterface $resourceShareClient
     */
    public function __construct(
        ResourceSharePageToResourceShareClientInterface $resourceShareClient
    ) {
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
}
