<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\MultiCartWidget\Widget;

use Spryker\Yves\Kernel\Widget\AbstractWidget;

/**
 * @method \SprykerShop\Yves\MultiCartWidget\MultiCartWidgetFactory getFactory()
 */
class MultiCartMenuItemWidget extends AbstractWidget
{
    protected const PAGE_KEY_MULTI_CART = 'multiCart';

    /**
     * @var string
     */
    protected $activePage;

    /**
     * @param string $activePage
     */
    public function __construct(string $activePage)
    {
        $this->activePage = $activePage;
        $this->addParameter('isActivePage', $this->isMultiCartPageActive());
    }

    /**
     * @return string
     */
    public static function getName(): string
    {
        return 'MultiCartMenuItemWidget';
    }

    /**
     * @return string
     */
    public static function getTemplate(): string
    {
        return '@MultiCartWidget/views/cart-list-menu-item/cart-list-menu-item.twig';
    }

    /**
     * @return bool
     */
    protected function isMultiCartPageActive(): bool
    {
        return $this->activePage === static::PAGE_KEY_MULTI_CART;
    }
}
