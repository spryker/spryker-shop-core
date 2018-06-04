<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CompanyUserInvitationPage\Dependency\Client;

class CompanyUserInvitationPageToSessionClientBridge implements CompanyUserInvitationPageToSessionClientInterface
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
     * @param string $name The attribute name
     * @param mixed $default The default value if not found.
     *
     * @return mixed
     */
    public function get($name, $default = null)
    {
        return $this->sessionClient->get($name, $default);
    }

    /**
     * @param string $name
     * @param mixed $value
     *
     * @return mixed
     */
    public function set($name, $value)
    {
        return $this->sessionClient->set($name, $value);
    }
}
