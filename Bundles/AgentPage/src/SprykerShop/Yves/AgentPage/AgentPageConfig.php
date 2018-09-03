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
     * @return string
     */
    public function getAgentFirewallRegex(): string
    {
        return $this->get(AgentPageConstants::AGENT_FIREWALL_REGEX, '\/agent(.+)?\/(?!login$).+|^(/en|/de)?/wishlist|^(/en|/de)?/shopping-list');
    }
}
