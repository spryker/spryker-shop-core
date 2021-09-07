<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\MultiCartWidget\Dependency\Plugin\SharedCartWidget;

interface SharedCartAddSeparateProductWidgetPluginInterface
{
    /**
     * @var string
     */
    public const NAME = 'SharedCartAddSeparateProductWidgetPlugin';

    /**
     * @api
     *
     * @return void
     */
    public function initialize(): void;
}
