<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\MultiCartWidget\Plugin\CustomerPage;

use Spryker\Yves\Kernel\Widget\AbstractWidgetPlugin;
use SprykerShop\Yves\CustomerPage\Dependency\Plugin\MultiCartMenuItemWidget\MultiCartMenuItemWidgetPluginInterface;
use SprykerShop\Yves\MultiCartWidget\Widget\MultiCartMenuItemWidget;

/**
 * @deprecated Use \SprykerShop\Yves\MultiCartWidget\Widget\MultiCartMenuItemWidget instead.
 */
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
        $widget = new MultiCartMenuItemWidget($activePage);

        $this->parameters = $widget->getParameters();
    }

    /**
     * {@inheritDoc}
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
     * {@inheritDoc}
     *
     * @api
     *
     * @return string
     */
    public static function getTemplate(): string
    {
        return MultiCartMenuItemWidget::getTemplate();
    }
}
