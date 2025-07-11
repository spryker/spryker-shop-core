<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CustomerPage\Dependency\Client;

class CustomerPageToSessionClientBridge implements CustomerPageToSessionClientInterface
{
    /**
     * @var \Spryker\Client\Session\SessionClientInterface
     */
    protected $sessionClient;

    /**
     * @param \Spryker\Client\Session\SessionClientInterface $sessionClient
     */
    public function __construct($sessionClient)
    {
        $this->sessionClient = $sessionClient;
    }

    /**
     * @param string $name
     * @param mixed $value
     *
     * @return void
     */
    public function set(string $name, mixed $value): void
    {
        $this->sessionClient->set($name, $value);
    }

    /**
     * @param string $name
     *
     * @return mixed
     */
    public function remove(string $name)
    {
        return $this->sessionClient->remove($name);
    }
}
