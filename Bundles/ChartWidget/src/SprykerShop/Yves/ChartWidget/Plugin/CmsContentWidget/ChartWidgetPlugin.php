<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ChartWidget\Plugin\CmsContentWidget;

use Spryker\Yves\Kernel\Widget\AbstractWidgetPlugin;
use SprykerShop\Yves\CmsContentWidgetChartConnector\Dependency\Plugin\ChartWidget\ChartWidgetPluginInterface;

/**
 * @method \SprykerShop\Yves\ChartWidget\ChartWidgetFactory getFactory()
 */
class ChartWidgetPlugin extends AbstractWidgetPlugin implements ChartWidgetPluginInterface
{
    /**
     * @param array $chart
     * @param string|null $dataIdentifier
     *
     * @return void
     */
    public function initialize(array $chart, $dataIdentifier = null): void
    {
        $this
            ->addParameter('chart', $chart)
            ->addParameter('dataIdentifier', $dataIdentifier);
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
        return '@ChartWidget/_cms-content-widget/chart.twig';
    }
}
