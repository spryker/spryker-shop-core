<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CustomerPage\Formatter;

use SprykerShop\Yves\CustomerPage\CustomerPageConfig;
use SprykerShop\Yves\CustomerPage\Dependency\Client\CustomerPageToStoreClientInterface;

class LoginCheckUrlFormatter implements LoginCheckUrlFormatterInterface
{
    /**
     * @var string
     */
    protected const ROUTE_CHECK_PATH = '/login_check';

    /**
     * @var string
     */
    protected const ROUTE_WITH_STORE_PLACEHOLDER = '/%s%s';

    /**
     * @param \SprykerShop\Yves\CustomerPage\CustomerPageConfig $customerPageConfig
     * @param string $localeName
     * @param \SprykerShop\Yves\CustomerPage\Dependency\Client\CustomerPageToStoreClientInterface $storeClient
     */
    public function __construct(
        protected CustomerPageConfig $customerPageConfig,
        protected string $localeName,
        protected CustomerPageToStoreClientInterface $storeClient
    ) {
    }

    /**
     * @return string
     */
    public function getLoginCheckPath(): string
    {
        $loginCheckPath = static::ROUTE_CHECK_PATH;
        if ($this->customerPageConfig->isLocaleInLoginCheckPath()) {
            $loginCheckPath = $this->getDefaultLocalePrefix() . $loginCheckPath;
        }

        /* Required by infrastructure, exists only for BC with DMS ON mode. */
        if ($this->customerPageConfig->isStoreRoutingEnabled()) {
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
