<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\HealthCheckPage;

use Spryker\Yves\Kernel\AbstractBundleConfig;

class HealthCheckPageConfig extends AbstractBundleConfig
{
    /**
     * @return string[]
     */
    public function getAvailableHealthCheckServices(): array
    {
        return [];
    }
}
