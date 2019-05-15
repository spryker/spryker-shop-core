<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\OauthCompanyUserCustomerPageConnector;

use Spryker\Yves\Kernel\AbstractFactory;
use SprykerShop\Yves\OauthCompanyUserCustomerPageConnector\Dependency\Client\OauthCompanyUserCustomerPageConnectorToOauthCompanyUserClientInterface;

class OauthCompanyUserCustomerPageConnectorFactory extends AbstractFactory
{
    /**
     * @return \SprykerShop\Yves\OauthCompanyUserCustomerPageConnector\Dependency\Client\OauthCompanyUserCustomerPageConnectorToOauthCompanyUserClientInterface
     */
    public function getOauthCompanyUserClient(): OauthCompanyUserCustomerPageConnectorToOauthCompanyUserClientInterface
    {
        return $this->getProvidedDependency(OauthCompanyUserCustomerPageConnectorDependencyProvider::CLIENT_OAUTH_COMPANY_USER);
    }
}
