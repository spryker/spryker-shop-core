<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\LanguageSwitcherWidget\Widget;

use Generated\Shared\Transfer\UrlStorageTransfer;
use Spryker\Yves\Kernel\Widget\AbstractWidget;

/**
 * @method \SprykerShop\Yves\LanguageSwitcherWidget\LanguageSwitcherWidgetFactory getFactory()
 * @method \SprykerShop\Yves\LanguageSwitcherWidget\LanguageSwitcherWidgetConfig getConfig()
 */
class LanguageSwitcherWidget extends AbstractWidget
{
    /**
     * @param string $pathInfo
     * @param string $queryString
     * @param string $requestUri
     */
    public function __construct(string $pathInfo, $queryString, string $requestUri)
    {
        $languages = $this->getLanguages($pathInfo, $queryString, $requestUri);

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
     * @param string $queryString
     * @param string $requestUri
     *
     * @return array<string>
     */
    protected function getLanguages(string $pathInfo, $queryString, string $requestUri): array
    {
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

        if (!empty($localeUrls)) {
            return $this->attachLocaleUrlsFromStorageToLanguages($locales, $localeUrls, $queryString);
        }

        return $this->attachLocaleUrlsToLanguages($locales, $requestUri);
    }

    /**
     * @param array $locales
     * @param array $localeUrls
     * @param string $queryString
     *
     * @return array
     */
    protected function attachLocaleUrlsFromStorageToLanguages(
        array $locales,
        array $localeUrls,
        $queryString
    ): array {
        $languages = [];
        foreach ($locales as $locale) {
            $language = $this->getLanguageFromLocale($locale);
            foreach ($localeUrls as $localeUrl) {
                if ($localeUrl[UrlStorageTransfer::LOCALE_NAME] === $locale) {
                    $languages[$language] = $this->getLocaleUrlWithQueryString(
                        $localeUrl[UrlStorageTransfer::URL],
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
     * @param array $locales
     * @param string $requestUri
     *
     * @return array
     */
    protected function attachLocaleUrlsToLanguages(array $locales, string $requestUri): array
    {
        $currentUrl = $requestUri;
        $languages = [];
        foreach ($locales as $locale) {
            $language = $this->getLanguageFromLocale($locale);
            $languages[$language] = $this->replaceCurrentUrlLanguage($currentUrl, array_keys($locales), $language);
        }

        return $languages;
    }

    /**
     * @param string $currentUrl
     * @param array $languages
     * @param string $replacementLanguage
     *
     * @return string
     */
    protected function replaceCurrentUrlLanguage(string $currentUrl, array $languages, string $replacementLanguage): string
    {
        if (preg_match('~/(' . implode('|', $languages) . ')/~', $currentUrl)) {
            return preg_replace('~/(' . implode('|', $languages) . ')~', '/' . $replacementLanguage, $currentUrl, 1);
        }

        return rtrim('/' . $replacementLanguage . $currentUrl, '/');
    }

    /**
     * @param string $locale
     *
     * @return string
     */
    protected function getLanguageFromLocale(string $locale): string
    {
        return substr($locale, 0, strpos($locale, '_'));
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
     * @param array $languages
     *
     * @return array
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
