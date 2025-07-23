<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\AgentPage;

use Spryker\Yves\Kernel\AbstractBundleConfig;
use SprykerShop\Shared\AgentPage\AgentPageConstants;

class AgentPageConfig extends AbstractBundleConfig
{
    /**
     * @api
     *
     * @return string
     */
    public function getAgentFirewallRegex(): string
    {
        return $this->get(AgentPageConstants::AGENT_FIREWALL_REGEX, '\/agent(.+)?\/(?!login$).+');
    }

    /**
     * Specification:
     * - Controls if the locale stub is added to the /agent/login_check path.
     * - False means the /agent/login_check path does not have locale.
     *
     * @api
     *
     * @deprecated Will be removed without replacement. In the future, the locale-specific URL will be used.
     *
     * @return bool
     */
    public function isLocaleInLoginCheckPath(): bool
    {
        return false;
    }

    /**
     * Specification:
     * - Returns true if the store routing is enabled.
     *
     * @api
     *
     * @return bool
     */
    public function isStoreRoutingEnabled(): bool
    {
        return $this->get(AgentPageConstants::IS_STORE_ROUTING_ENABLED, false);
    }
}
