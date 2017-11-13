<?php

/**
 * This file is part of the Spryker Demoshop.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerShop\Yves\WebProfilerWidget;

use SprykerShop\Shared\WebProfilerWidget\WebProfilerWidgetConstants;
use Spryker\Yves\Kernel\AbstractBundleConfig;

class WebProfilerWidgetConfig extends AbstractBundleConfig
{
    /**
     * @return bool
     */
    public function isWebProfilerEnabled()
    {
        return $this->get(WebProfilerWidgetConstants::ENABLE_WEB_PROFILER, false);
    }
}
