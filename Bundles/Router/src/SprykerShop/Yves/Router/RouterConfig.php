<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\Router;

use Spryker\Yves\Kernel\AbstractBundleConfig;
use SprykerShop\Yves\Router\Generator\UrlGenerator;
use SprykerShop\Yves\Router\UrlMatcher\RedirectableUrlMatcher;

class RouterConfig extends AbstractBundleConfig
{
    /**
     * @return array
     */
    public function getRouterConfiguration(): array
    {
        return [
            'cache_dir' => $this->getCachePathIfCacheEnabled(),
            'generator_class' => UrlGenerator::class,
            'matcher_class' => RedirectableUrlMatcher::class,
            'matcher_base_class' => RedirectableUrlMatcher::class,
        ];
    }

    /**
     * @return array
     */
    public function getFallbackRouterConfiguration(): array
    {
        $routerConfiguration = $this->getRouterConfiguration();
        $routerConfiguration['cache_dir'] = null;

        return $routerConfiguration;
    }

    /**
     * @return string|null
     */
    protected function getCachePathIfCacheEnabled(): ?string
    {
        if ($this->get(RouterEnvironmentConfigConstantsYves::IS_CACHE_ENABLED, true)) {
            $defaultCachePath = APPLICATION_ROOT_DIR . '/data/' . APPLICATION_STORE . '/cache/' . APPLICATION . '/routing';

            return $this->get(RouterEnvironmentConfigConstantsYves::CACHE_PATH, $defaultCachePath);
        }

        return null;
    }

    /**
     * @return bool
     */
    public function isSslEnabled(): bool
    {
        return $this->get(RouterEnvironmentConfigConstantsYves::IS_SSL_ENABLED, true);
    }

    /**
     * @return string[]
     */
    public function getSslExcludedRouteNames(): array
    {
        return $this->get(RouterEnvironmentConfigConstantsYves::SSL_EXCLUDED_ROUTE_NAMES, []);
    }

    /**
     * @return string[]
     */
    public function getAllowedLanguages(): array
    {
        return [
            'de',
            'en',
        ];
    }

    /**
     * @return string[]
     */
    public function getAllowedStores(): array
    {
        return [
            'DE',
            'US',
        ];
    }
}
