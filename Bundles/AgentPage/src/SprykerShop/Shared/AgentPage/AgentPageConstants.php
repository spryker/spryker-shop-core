<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Shared\AgentPage;

/**
 * Declares global environment configuration keys. Do not use it for other class constants.
 */
interface AgentPageConstants
{
    /**
     * @var string
     */
    public const AGENT_FIREWALL_REGEX = 'AGENT_PAGE:AGENT_FIREWALL_REGEX';

    /**
     * Specification:
     * - Returns true if the store routing is enabled.
     *
     * @api
     *
     * @var string
     */
    public const IS_STORE_ROUTING_ENABLED = 'AGENT_PAGE:IS_STORE_ROUTING_ENABLED';
}
