<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\SecurityBlockerPage;

use Spryker\Yves\Kernel\AbstractBundleConfig;

class SecurityBlockerPageConfig extends AbstractBundleConfig
{
    /**
     * @uses \Spryker\Glue\SecurityBlockerRestApi\SecurityBlockerRestApiConfig::SECURITY_BLOCKER_CUSTOMER_ENTITY_TYPE
     * @var string
     */
    public const SECURITY_BLOCKER_CUSTOMER_ENTITY_TYPE = 'customer';

    /**
     * @uses \Spryker\Glue\SecurityBlockerRestApi\SecurityBlockerRestApiConfig::SECURITY_BLOCKER_AGENT_ENTITY_TYPE
     * @var string
     */
    public const SECURITY_BLOCKER_AGENT_ENTITY_TYPE = 'agent';

    /**
     * Specification:
     * - Controls if local prefix is used in the /login_check path used for customer.
     *
     * @api
     *
     * @uses {@link \SprykerShop\Yves\CustomerPage\CustomerPageConfig::isLocaleInLoginCheckPath()}
     *
     * @deprecated Will be removed without replacement. In the future, the locale-specific URL will be used.
     *
     * @return bool
     */
    public function isLocaleInCustomerLoginCheckPath(): bool
    {
        return false;
    }

    /**
     * Specification:
     * - Controls if local prefix is used in the /agent/login_check path used for the agent.
     *
     * @api
     *
     * @uses {@link \SprykerShop\Yves\AgentPage\AgentPageConfig::isLocaleInLoginCheckPath()}
     *
     * @deprecated Will be removed without replacement. In the future, the locale-specific URL will be used.
     *
     * @return bool
     */
    public function isLocaleInAgentLoginCheckPath(): bool
    {
        return false;
    }
}
