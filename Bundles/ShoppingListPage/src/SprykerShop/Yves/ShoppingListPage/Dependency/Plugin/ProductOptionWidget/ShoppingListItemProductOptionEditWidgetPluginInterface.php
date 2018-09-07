<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ShoppingListPage\Dependency\Plugin\ProductOptionWidget;

use Spryker\Yves\Kernel\Dependency\Plugin\WidgetPluginInterface;

interface ShoppingListItemProductOptionEditWidgetPluginInterface extends WidgetPluginInterface
{
    public const NAME = 'ShoppingListItemProductOptionEditWidgetPlugin';

    /**
     * @param \Symfony\Component\Form\ChoiceList\View\ChoiceView[] $productOptionGroups
     * @param string $productOptionDropdownName
     *
     * @return void
     */
    public function initialize(array $productOptionGroups, string $productOptionDropdownName): void;
}
