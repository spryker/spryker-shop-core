<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ChartWidget\Plugin\CmsContentWidgetChartConnector;

use Spryker\Yves\Kernel\Widget\AbstractWidgetPlugin;
use SprykerShop\Yves\ChartWidget\Widget\ChartWidget;
use SprykerShop\Yves\CmsContentWidgetChartConnector\Dependency\Plugin\ChartWidget\ChartWidgetPluginInterface;

/**
 * @deprecated Use \SprykerShop\Yves\ChartWidget\Widget\ChartWidget instead.
 */
class ChartWidgetPlugin extends AbstractWidgetPlugin implements ChartWidgetPluginInterface
{
    /**
     * @param string $chartPluginName
     * @param string|null $dataIdentifier
     *
     * @return void
     */
    public function initialize(string $chartPluginName, ?string $dataIdentifier = null): void
    {
        $widget = new ChartWidget($chartPluginName, $dataIdentifier);

        $this->parameters = $widget->getParameters();
    }

    /**
     * @return string
     */
    public static function getName(): string
    {
        return static::NAME;
    }

    /**
     * @return string
     */
    public static function getTemplate(): string
    {
        return ChartWidget::getTemplate();
    }
}
