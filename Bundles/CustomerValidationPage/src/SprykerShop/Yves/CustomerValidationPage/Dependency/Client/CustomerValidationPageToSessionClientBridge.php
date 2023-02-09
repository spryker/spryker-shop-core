<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CustomerValidationPage\Dependency\Client;

use Symfony\Component\HttpFoundation\Session\Storage\MetadataBag;

class CustomerValidationPageToSessionClientBridge implements CustomerValidationPageToSessionClientInterface
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
     * @return \Symfony\Component\HttpFoundation\Session\Storage\MetadataBag
     */
    public function getMetadataBag(): MetadataBag
    {
        return $this->sessionClient->getMetadataBag();
    }
}
