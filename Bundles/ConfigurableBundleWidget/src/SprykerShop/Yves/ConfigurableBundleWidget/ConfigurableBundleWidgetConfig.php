<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ConfigurableBundleWidget;

use Spryker\Yves\Kernel\AbstractBundleConfig;

class ConfigurableBundleWidgetConfig extends AbstractBundleConfig
{
    /**
     * @api
     *
     * @return bool
     */
    public function isQuantityChangeable(): bool
    {
        return false;
    }
}
