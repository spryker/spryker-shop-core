<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ResourceSharePage\ResourceShare;

use Generated\Shared\Transfer\ResourceShareRequestTransfer;
use Generated\Shared\Transfer\ResourceShareResponseTransfer;
use Generated\Shared\Transfer\ResourceShareTransfer;
use SprykerShop\Yves\ResourceSharePage\Dependency\Client\ResourceSharePageToCustomerClientInterface;
use SprykerShop\Yves\ResourceSharePage\Dependency\Client\ResourceSharePageToResourceShareClientInterface;

class ResourceShareActivator
{
    /**
     * @var \SprykerShop\Yves\ResourceSharePage\Dependency\Client\ResourceSharePageToCustomerClientInterface
     */
    protected $customerClient;

    /**
     * @var \SprykerShop\Yves\ResourceSharePage\Dependency\Client\ResourceSharePageToResourceShareClientInterface
     */
    protected $resourceShareClient;

    /**
     * @param \SprykerShop\Yves\ResourceSharePage\Dependency\Client\ResourceSharePageToCustomerClientInterface $customerClient
     * @param \SprykerShop\Yves\ResourceSharePage\Dependency\Client\ResourceSharePageToResourceShareClientInterface $resourceShareClient
     */
    public function __construct(
        ResourceSharePageToCustomerClientInterface $customerClient,
        ResourceSharePageToResourceShareClientInterface $resourceShareClient
    ) {
        $this->customerClient = $customerClient;
        $this->resourceShareClient = $resourceShareClient;
    }

    /**
     * @param string $resourceShareUuid
     *
     * @return \Generated\Shared\Transfer\ResourceShareResponseTransfer
     */
    public function activateResourceShare(string $resourceShareUuid): ResourceShareResponseTransfer
    {
        $customerTransfer = $this->customerClient->getCustomer();

        $resourceShareTransfer = (new ResourceShareTransfer())
            ->setUuid($resourceShareUuid);

        $resourceShareRequestTransfer = (new ResourceShareRequestTransfer())
            ->setResourceShare($resourceShareTransfer)
            ->setCustomer($customerTransfer);

        $resourceShareResponseTransfer = $this->resourceShareClient->activateResourceShare($resourceShareRequestTransfer);

        return $resourceShareResponseTransfer;
    }
}
