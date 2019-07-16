<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Shared\WebProfilerWidget;

/**
 * Declares global environment configuration keys. Do not use it for other class constants.
 */
interface WebProfilerWidgetConstants
{
    /**
     * Specification:
     * - Enable/disable the web profiler.
     *
     * @api
     */
    public const IS_WEB_PROFILER_ENABLED = 'WEB_PROFILER_WIDGET_YVES:IS_WEB_PROFILER_ENABLED';

    /**
     * Specification:
     * - Path to the profiler cache directory.
     *
     * @api
     */
    public const PROFILER_CACHE_DIRECTORY = 'WEB_PROFILER_WIDGET_YVES:PROFILER_CACHE_DIRECTORY';
}
