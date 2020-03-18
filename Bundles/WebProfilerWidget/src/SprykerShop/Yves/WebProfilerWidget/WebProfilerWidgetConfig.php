<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\WebProfilerWidget;

use ReflectionClass;
use Spryker\Shared\Kernel\Store;
use Spryker\Yves\Kernel\AbstractBundleConfig;
use SprykerShop\Shared\WebProfilerWidget\WebProfilerWidgetConstants;
use Symfony\Bundle\WebProfilerBundle\EventListener\WebDebugToolbarListener;

class WebProfilerWidgetConfig extends AbstractBundleConfig
{
    /**
     * @api
     *
     * @return bool
     */
    public function isWebProfilerEnabled()
    {
        return $this->get(WebProfilerWidgetConstants::IS_WEB_PROFILER_ENABLED, false);
    }

    /**
     * @api
     *
     * @return string[]
     */
    public function getWebProfilerTemplatePaths(): array
    {
        $reflectionClass = new ReflectionClass(WebDebugToolbarListener::class);

        return [
            dirname(dirname((string)$reflectionClass->getFileName())) . '/Resources/views',
        ];
    }

    /**
     * @api
     *
     * @return string
     */
    public function getProfilerCacheDirectory(): string
    {
        $defaultPath = APPLICATION_ROOT_DIR . '/data/' . Store::getInstance()->getStoreName() . '/cache/profiler';

        return $this->get(WebProfilerWidgetConstants::PROFILER_CACHE_DIRECTORY, $defaultPath);
    }
}
