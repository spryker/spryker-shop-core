<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ShopLayoutExtension\Dependency\Plugin\LanguageSwitcherWidget;

use Symfony\Component\HttpFoundation\Request;

interface LanguageSwitcherWidgetPluginInterface
{
    const NAME = 'LanguageSwitcherWidgetPlugin';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return void
     */
    public function initialize(Request $request): void;
}
