<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CmsContentWidgetChartConnector\Dependency\Plugin\ChartWidget;

use Spryker\Yves\Kernel\Dependency\Plugin\WidgetPluginInterface;

interface ChartWidgetPluginInterface extends WidgetPluginInterface
{
    public const NAME = 'ChartWidgetPlugin';

    /**
     * @param array $chart
     * @param string|null $dataIdentifier
     *
     * @return void
     */
    public function initialize(array $chart, $dataIdentifier = null): void;
}
