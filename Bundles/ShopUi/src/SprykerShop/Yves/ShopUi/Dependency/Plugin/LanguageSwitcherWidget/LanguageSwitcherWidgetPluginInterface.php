<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ShopUi\Dependency\Plugin\LanguageSwitcherWidget;

use Spryker\Yves\Kernel\Dependency\Plugin\WidgetPluginInterface;

/**
 * @deprecated Use \SprykerShop\Yves\LanguageSwitcherWidget\Widget\LanguageSwitcherWidget instead.
 */
interface LanguageSwitcherWidgetPluginInterface extends WidgetPluginInterface
{
    public const NAME = 'LanguageSwitcherWidgetPlugin';

    /**
     * @api
     *
     * @param string $pathInfo
     * @param string $queryString
     * @param string $requestUri
     *
     * @return void
     */
    public function initialize(string $pathInfo, $queryString, string $requestUri): void;
}
