<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerShop\Zed\DateTimeConfiguratorPageExample;

use Spryker\Zed\Kernel\AbstractBundleConfig;

class DateTimeConfiguratorPageExampleConfig extends AbstractBundleConfig
{
    /**
     * @var string
     */
    protected const FRONTEND_TARGET_PATH = '/public/Configurator/dist';

    /**
     * @var string
     */
    protected const FRONTEND_ORIGIN_PATH = '../../Configurator/DateTimeConfiguratorPageExample/Theme/ConfiguratorApplication/dist';

    /**
     * Path to the built configurator frontend directory.
     *
     * @api
     *
     * @return string
     */
    public function getFrontendOriginPath(): string
    {
        return sprintf('%s/%s', __DIR__, static::FRONTEND_ORIGIN_PATH);
    }

    /**
     * Path to the configurator frontend web root directory.
     *
     * @api
     *
     * @return string
     */
    public function getFrontendTargetPath(): string
    {
        return sprintf('%s/%s', APPLICATION_ROOT_DIR, static::FRONTEND_TARGET_PATH);
    }
}
