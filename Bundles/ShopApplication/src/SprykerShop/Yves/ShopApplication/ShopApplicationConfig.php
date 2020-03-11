<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ShopApplication;

use Spryker\Shared\Application\ApplicationConstants;
use Spryker\Yves\Kernel\AbstractBundleConfig;
use SprykerShop\Shared\ShopApplication\ShopApplicationConstants;

class ShopApplicationConfig extends AbstractBundleConfig
{
    protected const FORM_RESOURCES_DIR = __DIR__ . '/Resources/views/Form';

    /**
     * @api
     *
     * @return string[]
     */
    public function getFormThemes()
    {
        return [
            'core_form_div_layout.html.twig',
        ];
    }

    /**
     * @api
     *
     * @return bool
     */
    public function useViewParametersToRenderTwig(): bool
    {
        return false;
    }

    /**
     * @api
     *
     * @deprecated Will be removed without replacement.
     *
     * @return bool
     */
    public function isSslEnabled(): bool
    {
        return $this->get(ApplicationConstants::YVES_SSL_ENABLED, true);
    }

    /**
     * @api
     *
     * @return string
     */
    public function getFormResourcesDir(): string
    {
        return static::FORM_RESOURCES_DIR;
    }

    /**
     * @api
     *
     * @return string[]
     */
    public function getShopApplicationResources(): array
    {
        return [
            $this->getFormResourcesDir(),
        ];
    }

    /**
     * @api
     *
     * @return bool
     */
    public function isDebugModeEnabled(): bool
    {
        return $this->get(ShopApplicationConstants::ENABLE_APPLICATION_DEBUG, false);
    }

    /**
     * @api
     *
     * @return string
     */
    public function getTwigEnvironmentName(): string
    {
        return $this->get(ShopApplicationConstants::TWIG_ENVIRONMENT_NAME, $this->getTwigEnvironmentNameDefaultValue());
    }

    /**
     * @deprecated Will be removed without replacement.
     *
     * @return string
     */
    protected function getTwigEnvironmentNameDefaultValue(): string
    {
        return APPLICATION_ENV;
    }
}
