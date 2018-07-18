<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ChartWidget\Plugin\Twig;

class TwigBarChartPlugin extends AbstractTwigChartPlugin
{
    public const TWIG_FUNCTION_NAME = 'barChart';

    /**
     * @return string
     */
    protected function getTemplateName(): string
    {
        return '@ChartWidget/_template/bar-chart.twig';
    }
}
