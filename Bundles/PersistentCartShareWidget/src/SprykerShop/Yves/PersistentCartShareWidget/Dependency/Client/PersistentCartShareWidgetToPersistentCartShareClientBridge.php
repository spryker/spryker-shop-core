<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\PersistentCartShareWidget\Dependency\Client;

class PersistentCartShareWidgetToPersistentCartShareClientBridge implements PersistentCartShareWidgetToPersistentCartShareClientInterface
{
    /**
     * @var \Spryker\Client\PersistentCartShare\PersistentCartShareClientInterface
     */
    protected $persistentCartShareClient;

    /**
     * @param \Spryker\Client\Customer\CustomerClientInterface $persistentCartShareClient
     */
    public function __construct($persistentCartShareClient)
    {
        $this->persistentCartShareClient = $persistentCartShareClient;
    }

    /**
     * @return array
     */
    public function getCartShareOptions()
    {
        return $this->persistentCartShareClient->getCartShareOptions();
    }
}
