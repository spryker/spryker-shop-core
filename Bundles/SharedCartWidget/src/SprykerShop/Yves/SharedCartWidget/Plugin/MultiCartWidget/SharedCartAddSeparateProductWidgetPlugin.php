<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\SharedCartWidget\Plugin\MultiCartWidget;

use Spryker\Yves\Kernel\Widget\AbstractWidgetPlugin;
use SprykerShop\Yves\MultiCartWidget\Dependency\Plugin\SharedCartWidget\SharedCartAddSeparateProductWidgetPluginInterface;
use SprykerShop\Yves\SharedCartWidget\Widget\SharedCartAddSeparateProductWidget;

/**
 * @deprecated Use \SprykerShop\Yves\SharedCartWidget\Widget\SharedCartAddSeparateProductWidget instead.
 */
class SharedCartAddSeparateProductWidgetPlugin extends AbstractWidgetPlugin implements SharedCartAddSeparateProductWidgetPluginInterface
{
    /**
     * @return void
     */
    public function initialize(): void
    {
        $widget = new SharedCartAddSeparateProductWidget();

        $this->parameters = $widget->getParameters();
    }

    /**
     * Specification:
     * - Returns the name of the widget as it's used in templates.
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
     * Specification:
     * - Returns the the template file path to render the widget.
     *
     * @api
     *
     * @return string
     */
    public static function getTemplate()
    {
        return SharedCartAddSeparateProductWidget::getTemplate();
    }
}
