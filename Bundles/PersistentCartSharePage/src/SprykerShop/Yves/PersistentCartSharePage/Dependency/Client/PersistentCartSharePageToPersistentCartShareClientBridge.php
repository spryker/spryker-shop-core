<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\PersistentCartSharePage\Dependency\Client;

use Generated\Shared\Transfer\QuoteResponseTransfer;
use Generated\Shared\Transfer\ResourceShareRequestTransfer;

class PersistentCartSharePageToPersistentCartShareClientBridge implements PersistentCartSharePageToPersistentCartShareClientInterface
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
     * @param \Generated\Shared\Transfer\ResourceShareRequestTransfer $resourceShareRequestTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function getPreviewQuoteResourceShare(ResourceShareRequestTransfer $resourceShareRequestTransfer): QuoteResponseTransfer
    {
        return $this->persistentCartShareClient->getPreviewQuoteResourceShare($resourceShareRequestTransfer);
    }
}
