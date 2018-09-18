<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\MultiCartWidget\Plugin\CustomerPage;

use Spryker\Yves\Kernel\Widget\AbstractWidgetPlugin;
use SprykerShop\Yves\CustomerPage\Dependency\Plugin\MultiCartMenuItemWidget\MultiCartMenuItemWidgetPluginInterface;

class MultiCartMenuItemWidgetPlugin extends AbstractWidgetPlugin implements MultiCartMenuItemWidgetPluginInterface
{
    protected const PAGE_KEY_MULTI_CART = 'multiCart';

    /**
     * @var string
     */
    protected $activePage;

    /**
     * @param string $activePage
     *
     * @return void
     */
    public function initialize(string $activePage): void
    {
        $this->activePage = $activePage;
        $this->addParameter('isActivePage', $this->isMultiCartPageActive());
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @return string
     */
    public static function getName(): string
    {
        return static::NAME;
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
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
