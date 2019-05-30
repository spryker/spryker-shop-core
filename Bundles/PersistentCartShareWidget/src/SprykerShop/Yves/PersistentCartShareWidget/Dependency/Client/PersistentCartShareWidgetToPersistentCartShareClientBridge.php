<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\PersistentCartShareWidget\Dependency\Client;

use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\ResourceShareRequestTransfer;
use Generated\Shared\Transfer\ResourceShareResponseTransfer;

class PersistentCartShareWidgetToPersistentCartShareClientBridge implements PersistentCartShareWidgetToPersistentCartShareClientInterface
{
    /**
     * @var \Spryker\Client\PersistentCartShare\PersistentCartShareClientInterface
     */
    protected $persistentCartShareClient;

    /**
     * @param \Spryker\Client\PersistentCartShare\PersistentCartShareClientInterface $persistentCartShareClient
     */
    public function __construct($persistentCartShareClient)
    {
        $this->persistentCartShareClient = $persistentCartShareClient;
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer|null $customerTransfer
     *
     * @return string[][]
     */
    public function getCartShareOptions(?CustomerTransfer $customerTransfer): array
    {
        return $this->persistentCartShareClient->getCartShareOptions($customerTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ResourceShareRequestTransfer $resourceShareRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ResourceShareResponseTransfer
     */
    public function generateCartResourceShare(ResourceShareRequestTransfer $resourceShareRequestTransfer): ResourceShareResponseTransfer
    {
        return $this->persistentCartShareClient->generateCartResourceShare($resourceShareRequestTransfer);
    }
}
