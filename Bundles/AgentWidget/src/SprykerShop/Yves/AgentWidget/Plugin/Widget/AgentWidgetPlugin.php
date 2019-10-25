<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\AgentWidget\Plugin\Widget;

use Spryker\Yves\Kernel\Widget\AbstractWidgetPlugin;
use SprykerShop\Yves\AgentWidget\Widget\AgentControlBarWidget;
use SprykerShop\Yves\ShopUi\Dependency\Plugin\AgentWidget\AgentWidgetPluginInterface;

/**
 * @deprecated Use \SprykerShop\Yves\AgentWidget\Widget\AgentControlBarWidget instead.
 *
 * @method \SprykerShop\Yves\AgentWidget\AgentWidgetFactory getFactory()
 */
class AgentWidgetPlugin extends AbstractWidgetPlugin implements AgentWidgetPluginInterface
{
    /**
     * @return void
     */
    public function initialize(): void
    {
        $widget = new AgentControlBarWidget();

        $this->parameters = $widget->getParameters();
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return string
     */
    public static function getName()
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
    public static function getTemplate()
    {
        return AgentControlBarWidget::getTemplate();
    }
}
