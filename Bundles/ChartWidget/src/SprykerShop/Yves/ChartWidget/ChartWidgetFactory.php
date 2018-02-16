<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ChartWidget;

use Spryker\Yves\Kernel\AbstractFactory;

class ChartWidgetFactory extends AbstractFactory
{
    /**
     * @return string[]
     */
    public function getCmsContentWidgetChartWidgetPlugins(): array
    {
        return $this->getProvidedDependency(ChartWidgetDependencyProvider::PLUGIN_CMS_CONTENT_WIDGET_CHART_SUB_WIDGETS);
    }
}
