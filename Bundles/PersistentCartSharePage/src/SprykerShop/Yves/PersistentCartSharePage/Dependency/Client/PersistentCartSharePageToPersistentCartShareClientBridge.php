<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\PersistentCartSharePage\Dependency\Client;

use Generated\Shared\Transfer\QuoteResponseTransfer;

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
     * @param string $resourceShareUuid
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function getQuoteForPreview(string $resourceShareUuid): QuoteResponseTransfer
    {
        return $this->persistentCartShareClient->getQuoteByResourceShareUuid($resourceShareUuid);
    }
}
