<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\AgentPage\Formatter;

use SprykerShop\Yves\AgentPage\AgentPageConfig;

class LoginCheckUrlFormatter implements LoginCheckUrlFormatterInterface
{
    protected const ROUTE_CHECK_PATH = '/agent/login_check';

    /**
     * @var \SprykerShop\Yves\AgentPage\AgentPageConfig
     */
    protected $agentPageConfig;

    /**
     * @var string
     */
    protected $localeName;

    /**
     * @param \SprykerShop\Yves\AgentPage\AgentPageConfig $agentPageConfig
     * @param string $localeName
     */
    public function __construct(AgentPageConfig $agentPageConfig, string $localeName)
    {
        $this->agentPageConfig = $agentPageConfig;
        $this->localeName = $localeName;
    }

    /**
     * @return string
     */
    public function getLoginCheckPath(): string
    {
        $loginCheckPath = static::ROUTE_CHECK_PATH;
        if ($this->agentPageConfig->isLocaleInLoginCheckPath()) {
            $loginCheckPath = $this->getDefaultLocalePrefix() . $loginCheckPath;
        }

        return $loginCheckPath;
    }

    /**
     * @return string
     */
    protected function getDefaultLocalePrefix(): string
    {
        return '/' . mb_substr($this->localeName, 0, 2);
    }
}
