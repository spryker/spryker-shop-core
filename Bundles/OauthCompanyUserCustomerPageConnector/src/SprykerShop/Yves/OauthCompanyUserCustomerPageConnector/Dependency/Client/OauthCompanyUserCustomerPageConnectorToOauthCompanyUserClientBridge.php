<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\OauthCompanyUserCustomerPageConnector\Dependency\Client;

use Generated\Shared\Transfer\CustomerResponseTransfer;

class OauthCompanyUserCustomerPageConnectorToOauthCompanyUserClientBridge implements OauthCompanyUserCustomerPageConnectorToOauthCompanyUserClientInterface
{
    /**
     * @var \Spryker\Client\OauthCompanyUser\OauthCompanyUserClientInterface
     */
    protected $oauthCompanyUserClient;

    /**
     * @param \Spryker\Client\OauthCompanyUser\OauthCompanyUserClientInterface $oauthCompanyUserClient
     */
    public function __construct($oauthCompanyUserClient)
    {
        $this->oauthCompanyUserClient = $oauthCompanyUserClient;
    }

    /**
     * @param string $accessToken
     *
     * @return \Generated\Shared\Transfer\CustomerResponseTransfer
     */
    public function getCustomerByAccessToken(string $accessToken): CustomerResponseTransfer
    {
        return $this->oauthCompanyUserClient->getCustomerByAccessToken($accessToken);
    }
}
