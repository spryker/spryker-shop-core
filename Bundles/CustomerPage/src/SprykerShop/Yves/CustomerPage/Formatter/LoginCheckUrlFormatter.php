<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CustomerPage\Formatter;

use SprykerShop\Yves\CustomerPage\CustomerPageConfig;

class LoginCheckUrlFormatter implements LoginCheckUrlFormatterInterface
{
    protected const ROUTE_CHECK_PATH = '/login_check';

    /**
     * @var \SprykerShop\Yves\CustomerPage\CustomerPageConfig
     */
    protected $customerPageConfig;

    /**
     * @var string
     */
    protected $localeName;

    /**
     * @param \SprykerShop\Yves\CustomerPage\CustomerPageConfig $customerPageConfig
     * @param string $localeName
     */
    public function __construct(CustomerPageConfig $customerPageConfig, string $localeName)
    {
        $this->customerPageConfig = $customerPageConfig;
        $this->localeName = $localeName;
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
