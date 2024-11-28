<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\LanguageSwitcherWidget\Widget;

use Generated\Shared\Transfer\UrlStorageTransfer;
use InvalidArgumentException;
use Spryker\Yves\Kernel\Widget\AbstractWidget;

/**
 * @method \SprykerShop\Yves\LanguageSwitcherWidget\LanguageSwitcherWidgetFactory getFactory()
 * @method \SprykerShop\Yves\LanguageSwitcherWidget\LanguageSwitcherWidgetConfig getConfig()
 */
class LanguageSwitcherWidget extends AbstractWidget
{
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
    protected const REQUEST_CONTEXT_LOCALE = '_locale';

    /**
     * @param string $pathInfo
     * @param string $queryString
     * @param string $requestUri
     */
    public function __construct(string $pathInfo, $queryString, string $requestUri)
    {
        $languages = $this->getLanguages($pathInfo, $queryString);

        $this->addParameter('languages', $this->filterExcludedUrls($languages))
            ->addParameter('currentLanguage', $this->getCurrentLanguage());
    }

    /**
     * @return string
     */
    public static function getName(): string
    {
        return 'LanguageSwitcherWidget';
    }

    /**
     * @return string
     */
    public static function getTemplate(): string
    {
        return '@LanguageSwitcherWidget/views/switcher/switcher.twig';
    }

    /**
     * @param string $pathInfo
     * @param string|null $queryString
     *
     * @return array<string>
     */
    protected function getLanguages(string $pathInfo, ?string $queryString): array
    {
        $request = $this->getGlobalContainer()->get(static::SERVICE_REQUEST_STACK)->getCurrentRequest();
        $pathInfo = $request->attributes->get(static::REQUEST_ATTRIBUTE_PATH_INFO) ?? $pathInfo;

        $currentUrlStorage = $this->getFactory()
            ->getUrlStorageClient()
            ->findUrlStorageTransferByUrl($pathInfo);

        $localeUrls = [];
        if ($currentUrlStorage !== null && $currentUrlStorage->getLocaleUrls()->count() !== 0) {
            $localeUrls = (array)$currentUrlStorage->getLocaleUrls();
        }

        $locales = $this->getFactory()
            ->getLocaleClient()
            ->getLocales();

        if ($localeUrls) {
            return $this->attachLocaleUrlsFromStorageToLanguages($locales, $localeUrls, $queryString);
        }

        return $this->attachLocaleUrlsToLanguages($locales);
    }

    /**
     * @param array<string> $locales
     * @param array<mixed> $localeUrls
     * @param string|null $queryString
     *
     * @return array<string, string>
     */
    protected function attachLocaleUrlsFromStorageToLanguages(
        array $locales,
        array $localeUrls,
        ?string $queryString
    ): array {
        $languages = [];
        $routers = $this->getGlobalContainer()->get(static::SERVICE_ROUTERS);
        foreach ($locales as $locale) {
            $language = $this->getLanguageFromLocale($locale);
            foreach ($localeUrls as $localeUrl) {
                if ($localeUrl[UrlStorageTransfer::LOCALE_NAME] === $locale) {
                    $languages[$language] = $this->getLocaleUrlWithQueryString(
                        $routers->generate($localeUrl[UrlStorageTransfer::URL]),
                        $queryString,
                    );

                    break;
                }
            }
        }

        return $languages;
    }

    /**
     * @param string $url
     * @param string|null $queryString
     *
     * @return string
     */
    protected function getLocaleUrlWithQueryString(string $url, ?string $queryString): string
    {
        if ($queryString) {
            return $url . '?' . $queryString;
        }

        return $url;
    }

    /**
     * @param array<string> $locales
     *
     * @return array<string, string>
     */
    protected function attachLocaleUrlsToLanguages(array $locales): array
    {
        $languages = [];
        $currentLocale = $locales[$this->getFactory()->getLocaleClient()->getCurrentLanguage()];
        $request = $this->getGlobalContainer()->get(static::SERVICE_REQUEST_STACK)->getCurrentRequest();
        $route = $request->attributes->get(static::REQUEST_ATTRIBUTE_ROUTE);
        $parameters = $request->attributes->get(static::REQUEST_ATTRIBUTE_ROUTE_PARAMS, []);
        foreach ($locales as $locale) {
            $language = $this->getLanguageFromLocale($locale);
            $languages[$language] = $this->replaceCurrentUrlLanguage($locale, $currentLocale, $route, $parameters);
        }

        return $languages;
    }

    /**
     * @param string $locale
     * @param string $currentLocale
     * @param string $route
     * @param array<string, mixed> $parameters
     *
     * @return string
     */
    protected function replaceCurrentUrlLanguage(
        string $locale,
        string $currentLocale,
        string $route,
        array $parameters
    ): string {
        $this->setRouterLocale($locale);
        $generatedRoute = $this->generateRoute($route, $parameters);
        $this->setRouterLocale($currentLocale);

        return $generatedRoute;
    }

    /**
     * @param string $locale
     *
     * @return void
     */
    protected function setRouterLocale(string $locale): void
    {
        $routers = $this->getGlobalContainer()->get(static::SERVICE_ROUTERS);
        $context = $routers->getContext();
        $context->setParameter(static::REQUEST_CONTEXT_LOCALE, $locale);
        $routers->setContext($context);
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
     * @param string $locale
     *
     * @return string
     */
    protected function getLanguageFromLocale(string $locale): string
    {
        return substr($locale, 0, strpos($locale, '_') ?: 0);
    }

    /**
     * @return string
     */
    protected function getCurrentLanguage(): string
    {
        return $this->getFactory()
            ->getLocaleClient()
            ->getCurrentLanguage();
    }

    /**
     * @param array<string> $languages
     *
     * @return array<string, string>
     */
    protected function filterExcludedUrls(array $languages): array
    {
        $filteredLanguages = [];

        foreach ($languages as $locale => $url) {
            $filteredLanguages[$locale] = $this->filterLanguageUrl($url, $locale);
        }

        return $filteredLanguages;
    }

    /**
     * @param string $url
     * @param string $locale
     *
     * @return string
     */
    protected function filterLanguageUrl(string $url, string $locale): string
    {
        foreach ($this->getConfig()->getExcludedLanguageSwitcherRouteUrls() as $excludedRouteUrl) {
            if (mb_strpos($url, $excludedRouteUrl) !== false) {
                return sprintf('/%s', $locale);
            }
        }

        return $url;
    }
}
