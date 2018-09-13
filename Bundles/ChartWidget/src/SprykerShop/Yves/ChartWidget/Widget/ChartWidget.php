<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ChartWidget\Widget;

use Spryker\Yves\Kernel\Widget\AbstractWidget;

class ChartWidget extends AbstractWidget
{
    /**
     * @param string $chartPluginName
     * @param string|null $dataIdentifier
     */
    public function __construct(string $chartPluginName, ?string $dataIdentifier = null)
    {
        $this
            ->addParameter('chartPluginName', $chartPluginName)
            ->addParameter('dataIdentifier', $dataIdentifier);
    }

    /**
     * @return string
     */
    public static function getName(): string
    {
        return 'ChartWidget';
    }

    /**
     * @return string
     */
    public static function getTemplate(): string
    {
        return '@ChartWidget/views/chart/chart.twig';
    }
}
