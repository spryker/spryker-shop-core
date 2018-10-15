<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\QuickOrderPage\Dependency\Plugin\ProductSearchWidget;

use Spryker\Yves\Kernel\Dependency\Plugin\WidgetPluginInterface;

interface QuickOrderPageProductSearchWidgetPluginInterface extends WidgetPluginInterface
{
    public const NAME = 'QuickOrderPageProductSearchWidgetPlugin';

    /**
     * @api
     *
     * @param string $index
     * @param string $searchFieldName
     * @param string|null $searchFieldValue
     * @param int|null $searchResultLimit
     *
     * @return void
     */
    public function initialize(string $index, string $searchFieldName, ?string $searchFieldValue, ?int $searchResultLimit = null): void;
}
