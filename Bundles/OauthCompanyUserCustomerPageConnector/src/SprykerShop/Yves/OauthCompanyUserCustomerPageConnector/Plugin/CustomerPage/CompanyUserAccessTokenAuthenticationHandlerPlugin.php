<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\OauthCompanyUserCustomerPageConnector\Plugin\CustomerPage;

use Generated\Shared\Transfer\CustomerResponseTransfer;
use Spryker\Yves\Kernel\AbstractPlugin;
use SprykerShop\Yves\CustomerPageExtension\Dependency\Plugin\AccessTokenAuthenticationHandlerPluginInterface;

/**
 * @method \SprykerShop\Yves\OauthCompanyUserCustomerPageConnector\OauthCompanyUserCustomerPageConnectorFactory getFactory()
 */
class CompanyUserAccessTokenAuthenticationHandlerPlugin extends AbstractPlugin implements AccessTokenAuthenticationHandlerPluginInterface
{
    /**
     * {@inheritdoc}
     * - Retrieves customer by access token.
     *
     * @api
     *
     * @param string $accessToken
     *
     * @return \Generated\Shared\Transfer\CustomerResponseTransfer
     */
    public function getCustomerByToken(string $accessToken): CustomerResponseTransfer
    {
        return $this->getFactory()
            ->getOauthCompanyUserClient()
            ->getCustomerByAccessToken($accessToken);
    }
}
