<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\AgentPage\Formatter;

use SprykerShop\Yves\AgentPage\AgentPageConfig;
use SprykerShop\Yves\AgentPage\Dependency\Client\AgentPageToStoreClientInterface;

class LoginCheckUrlFormatter implements LoginCheckUrlFormatterInterface
{
    /**
     * @var string
     */
    protected const ROUTE_CHECK_PATH = '/agent/login_check';

    /**
     * @var string
     */
    protected const ROUTE_WITH_STORE_PLACEHOLDER = '/%s%s';

    /**
     * @param \SprykerShop\Yves\AgentPage\AgentPageConfig $agentPageConfig
     * @param string $localeName
     * @param \SprykerShop\Yves\AgentPage\Dependency\Client\AgentPageToStoreClientInterface $storeClient
     */
    public function __construct(
        protected AgentPageConfig $agentPageConfig,
        protected string $localeName,
        protected AgentPageToStoreClientInterface $storeClient
    ) {
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

        /* Required by infrastructure, exists only for BC with DMS ON mode. */
        if ($this->agentPageConfig->isStoreRoutingEnabled()) {
            $loginCheckPath = sprintf(static::ROUTE_WITH_STORE_PLACEHOLDER, $this->storeClient->getCurrentStore()->getNameOrFail(), $loginCheckPath);
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
