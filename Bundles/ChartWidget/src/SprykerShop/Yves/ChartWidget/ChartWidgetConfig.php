<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ChartWidget;

use Generated\Shared\Transfer\ChartLayoutTransfer;
use Spryker\Yves\Kernel\AbstractBundleConfig;

/**
 * @method \Spryker\Shared\Chart\ChartConfig getSharedConfig()
 */
class ChartWidgetConfig extends AbstractBundleConfig
{
    /**
     * @api
     *
     * @return \Generated\Shared\Transfer\ChartLayoutTransfer
     */
    public function getDefaultChartLayout(): ChartLayoutTransfer
    {
        return new ChartLayoutTransfer();
    }
}
