<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\SessionAgentValidation;

use Spryker\Yves\Kernel\AbstractBundleConfig;

class SessionAgentValidationConfig extends AbstractBundleConfig
{
    /**
     * @var string
     */
    protected const SESSION_ENTITY_TYPE = 'agent';

    /**
     * @var string
     */
    protected const AUTHENTICATION_LISTENER_FACTORY_TYPE = 'agent_session_validator';

    /**
     * @api
     *
     * @return string
     */
    public function getSessionEntityType(): string
    {
        return static::SESSION_ENTITY_TYPE;
    }

    /**
     * @api
     *
     * @return string
     */
    public function getAuthenticationListenerFactoryType(): string
    {
        return static::AUTHENTICATION_LISTENER_FACTORY_TYPE;
    }
}
