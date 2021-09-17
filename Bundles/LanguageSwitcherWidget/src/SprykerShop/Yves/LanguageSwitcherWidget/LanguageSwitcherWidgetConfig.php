<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\LanguageSwitcherWidget;

use Spryker\Yves\Kernel\AbstractBundleConfig;

class LanguageSwitcherWidgetConfig extends AbstractBundleConfig
{
    /**
     * @var array
     */
    protected const EXCLUDED_LANGUAGE_SWITCHER_ROUTE_URLS = [
        '/login_check',
        '/agent/login_check',
    ];

    /**
     * Specification:
     * - Returns a list of excluded URLs to be replaced by the default URL in `LanguageSwitcherWidget`.
     *
     * @api
     *
     * @return array<string>
     */
    public function getExcludedLanguageSwitcherRouteUrls(): array
    {
        return static::EXCLUDED_LANGUAGE_SWITCHER_ROUTE_URLS;
    }
}
