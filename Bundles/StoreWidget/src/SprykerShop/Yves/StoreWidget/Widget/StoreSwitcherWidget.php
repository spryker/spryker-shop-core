<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\StoreWidget\Widget;

use InvalidArgumentException;
use Spryker\Yves\Kernel\Widget\AbstractWidget;

/**
 * @method \SprykerShop\Yves\StoreWidget\StoreWidgetFactory getFactory()
 * @method \SprykerShop\Yves\StoreWidget\StoreWidgetConfig getConfig()
 */
class StoreSwitcherWidget extends AbstractWidget
{
    /**
     * @var string
     */
    protected const PARAMETER_CURRENT_STORE_NAME = 'currentStoreName';

    /**
     * @var string
     */
    protected const PARAMETER_STORE_NAMES = 'storeNames';

    /**
     * @var string
     */
    protected const PARAMETER_STORE_URLS = 'storeUrls';

    /**
     * @var string
     */
    protected const PARAMETER_IS_DYNAMIC_STORE_ENABLED = 'isDynamicStoreEnabled';

    /**
     * @var string
     */
    protected const SERVICE_REQUEST_STACK = 'request_stack';

    /**
     * @var string
     */
    protected const SERVICE_ROUTERS = 'routers';

    /**
     * @var string
     */
    protected const REQUEST_ATTRIBUTE_PATH_INFO = 'pathinfo';

    /**
     * @var string
     */
    protected const REQUEST_ATTRIBUTE_ROUTE = '_route';

    /**
     * @var string
     */
    protected const REQUEST_ATTRIBUTE_ROUTE_PARAMS = '_route_params';

    /**
     * @var string
     */
    protected const REQUEST_CONTEXT_STORE = 'store';

    /**
     * @var string
     */
    protected const REQUEST_CONTEXT_LOCALE = '_locale';

    /**
     * @var string
     */
    protected const STR_SEARCH = '_';

    /**
     * @var string
     */
    protected const STR_REPLACE = '-';

    /**
     * @var string
     */
    protected const FULL_LOCALE_PATTERN = '/^[a-z]{2}-[a-z]{2}$/';

    /**
     * @var string
     */
    protected const SHORT_LOCALE_PATTERN = '/^[a-z]{2}$/';

    /**
     * @var string
     */
    protected const REQUEST_URL_LOCALE = 'url_locale';

    public function __construct()
    {
        $this->addCurrentStoreParameter();
        $this->addStoreNamesParameter();
        $this->addIsDynamicStoreEnabledParameter();
        $this->addStoreUrlsParam();
    }

    /**
     * @return string
     */
    public static function getName(): string
    {
        return 'StoreSwitcher';
    }

    /**
     * @return string
     */
    public static function getTemplate(): string
    {
        return '@StoreWidget/views/switcher/switcher.twig';
    }

    /**
     * @return void
     */
    protected function addCurrentStoreParameter(): void
    {
        $this->addParameter(
            static::PARAMETER_CURRENT_STORE_NAME,
            $this->getFactory()->getStoreClient()->getCurrentStore()->getNameOrFail(),
        );
    }

    /**
     * @return void
     */
    protected function addStoreNamesParameter(): void
    {
        $this->addParameter(
            static::PARAMETER_STORE_NAMES,
            $this->getFactory()->getStoreStorageClient()->getStoreNames(),
        );
    }

    /**
     * @return void
     */
    protected function addStoreUrlsParam(): void
    {
        if ($this->getConfig()->isStoreRoutingEnabled() === false) {
            return;
        }

        $route = $this->getCurrentRoute();
        $parameters = $this->getCurrentRouteParameters();
        $urls = $this->generateStoreUrls($route, $parameters);

        $this->addParameter(static::PARAMETER_STORE_URLS, $urls);
    }

    /**
     * @param string $route
     * @param array<string, mixed> $parameters
     *
     * @return array<string, string>
     */
    protected function generateStoreUrls(string $route, array $parameters): array
    {
        $storeNames = $this->getFactory()->getStoreStorageClient()->getStoreNames();
        $urls = [];

        foreach ($storeNames as $storeName) {
            $route = $this->setRouterLocaleContext($route, $storeName);
            $this->setRouterStoreContext($storeName);
            $urls[$storeName] = $this->generateRoute($route, $parameters);
            $this->setRouterStoreContext();
            $this->setRouterLocaleContext($route);
        }

        return $urls;
    }

    /**
     * @return string
     */
    protected function getCurrentRoute(): string
    {
        $request = $this->getGlobalContainer()->get(static::SERVICE_REQUEST_STACK)->getCurrentRequest();

        return $request->attributes->get(static::REQUEST_ATTRIBUTE_PATH_INFO, $request->attributes->get(static::REQUEST_ATTRIBUTE_ROUTE));
    }

    /**
     * @param string $route
     * @param array<string, mixed> $parameters
     *
     * @return string
     */
    protected function generateRoute(string $route, array $parameters): string
    {
        $routers = $this->getGlobalContainer()->get(static::SERVICE_ROUTERS);

        try {
            return $routers->generate($route);
        } catch (InvalidArgumentException $exception) {
            return $routers->generate($route, $parameters);
        }
    }

    /**
     * @param string|null $storeName
     *
     * @return void
     */
    protected function setRouterStoreContext(?string $storeName = null): void
    {
        $routers = $this->getGlobalContainer()->get(static::SERVICE_ROUTERS);
        $context = $routers->getContext();
        $context->setParameter(static::REQUEST_CONTEXT_STORE, $storeName);
        $routers->setContext($context);
    }

    /**
     * @param string $route
     * @param string|null $storeName
     *
     * @return string
     */
    protected function setRouterLocaleContext(string $route, ?string $storeName = null): string
    {
        $routers = $this->getGlobalContainer()->get(static::SERVICE_ROUTERS);
        $context = $routers->getContext();

        if ($storeName === null) {
            if ($context->hasParameter(static::REQUEST_URL_LOCALE)) {
                $context->setParameter(static::REQUEST_CONTEXT_LOCALE, $context->getParameter(static::REQUEST_URL_LOCALE));
                $routers->setContext($context);
            }

            return $route;
        }

        $locale = $context->getParameter(static::REQUEST_CONTEXT_LOCALE);
        $context->setParameter(static::REQUEST_URL_LOCALE, $locale);
        $store = $this->getFactory()->getStoreStorageClient()->findStoreByName($storeName);

        if ($store === null || in_array($locale, $store->getAvailableLocaleIsoCodes(), true)) {
            return $route;
        }

        $context->setParameter(static::REQUEST_CONTEXT_LOCALE, $store->getDefaultLocaleIsoCodeOrFail());
        $routers->setContext($context);

        return $this->updateRouteWithLocale($route, $store->getDefaultLocaleIsoCodeOrFail());
    }

    /**
     * @param string $route
     * @param string $locale
     *
     * @return string
     */
    protected function updateRouteWithLocale(string $route, string $locale): string
    {
        $urlParts = explode('/', ltrim($route, '/'), 2);

        if (preg_match(static::SHORT_LOCALE_PATTERN, $urlParts[0])) {
            $urlParts[0] = mb_substr($locale, 0, 2);

            return '/' . implode('/', $urlParts);
        }
        if (preg_match(static::FULL_LOCALE_PATTERN, $urlParts[0])) {
            $urlParts[0] = $this->convertLocaleToFullFormat($locale);

            return '/' . implode('/', $urlParts);
        }

        return $route;
    }

    /**
     * @param string $locale
     *
     * @return string
     */
    protected function convertLocaleToFullFormat(string $locale): string
    {
        return str_replace(static::STR_SEARCH, static::STR_REPLACE, strtolower($locale));
    }

    /**
     * @return array<string, mixed>
     */
    protected function getCurrentRouteParameters(): array
    {
        $request = $this->getGlobalContainer()->get(static::SERVICE_REQUEST_STACK)->getCurrentRequest();

        return $request->attributes->get(static::REQUEST_ATTRIBUTE_ROUTE_PARAMS, []);
    }

    /**
     * Required by infrastructure, exists only for BC with DMS OFF mode.
     *
     * @return void
     */
    protected function addIsDynamicStoreEnabledParameter(): void
    {
        $this->addParameter(
            static::PARAMETER_IS_DYNAMIC_STORE_ENABLED,
            $this->getFactory()->getStoreClient()->isDynamicStoreEnabled(),
        );
    }
}
