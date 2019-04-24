<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\PersistentCartShareWidget\Dependency\Client;

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
     * @return array
     */
    public function getCartShareOptions(): array
    {
        return $this->persistentCartShareClient->getCartShareOptions();
    }

    /**
     * {@inheritDoc}
     */
    public function generateCartResourceShare(int $idQuote, string $shareOption): ResourceShareResponseTransfer
    {
        return $this->persistentCartShareClient->generateCartResourceShare($idQuote, $shareOption);
    }
}
