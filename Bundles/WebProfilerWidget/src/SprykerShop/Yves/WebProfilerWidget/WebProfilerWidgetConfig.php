<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\WebProfilerWidget;

use ReflectionClass;
use Spryker\Shared\WebProfiler\WebProfilerConstants;
use Spryker\Yves\Kernel\AbstractBundleConfig;
use Symfony\Bundle\WebProfilerBundle\EventListener\WebDebugToolbarListener;

class WebProfilerWidgetConfig extends AbstractBundleConfig
{
    /**
     * @return bool
     */
    public function isWebProfilerEnabled()
    {
        return $this->get(WebProfilerConstants::ENABLE_WEB_PROFILER, false);
    }

    /**
     * @return string[]
     */
    public function getWebProfilerTemplatePaths(): array
    {
        $reflectionClass = new ReflectionClass(WebDebugToolbarListener::class);

        return [
            dirname(dirname((string)$reflectionClass->getFileName())) . '/Resources/views',
        ];
    }
}
