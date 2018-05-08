<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\LanguageSwitcherWidget\Plugin\ShopLayout;

use Generated\Shared\Transfer\UrlStorageTransfer;
use Spryker\Yves\Kernel\Widget\AbstractWidgetPlugin;
use SprykerShop\Yves\ShopLayoutExtension\Dependency\Plugin\LanguageSwitcherWidget\LanguageSwitcherWidgetPluginInterface;

/**
 * @method \SprykerShop\Yves\LanguageSwitcherWidget\LanguageSwitcherWidgetFactory getFactory()
 */
class LanguageSwitcherWidgetPlugin extends AbstractWidgetPlugin implements LanguageSwitcherWidgetPluginInterface
{
    /**
     * @param string $pathInfo
     * @param string $queryString
     * @param string $requestUri
     *
     * @return void
     */
    public function initialize(
        string $pathInfo,
        $queryString,
        string $requestUri
    ): void {
        $currentUrlStorage = $this->getFactory()
            ->getUrlStorageClient()
            ->findUrlStorageTransferByUrl($pathInfo);
        $localeUrls = [];

        if ($currentUrlStorage !== null && $currentUrlStorage->getLocaleUrls()->count() !== 0) {
            $localeUrls = (array)$currentUrlStorage->getLocaleUrls();
        }

        $this->addParameter('languages', $this->getLanguages($localeUrls, $queryString, $requestUri))
            ->addParameter('currentLanguage', $this->getCurrentLanguage());
    }

    /**
     * @return string
     */
    public static function getName(): string
    {
        return static::NAME;
    }

    /**
     * @return string
     */
    public static function getTemplate(): string
    {
        return '@LanguageSwitcherWidget/_partials/language_switcher.twig';
    }

    /**
     * @param array $localeUrls
     * @param string $queryString
     * @param string $requestUri
     *
     * @return string[]
     */
    protected function getLanguages(
        array $localeUrls,
        $queryString,
        string $requestUri
    ): array {
        $locales = $this->getFactory()
            ->getStore()
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
                    $languages[$language] = $localeUrl[UrlStorageTransfer::URL] . '?' . $queryString;
                    break;
                }
            }
        }

        return $languages;
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
    protected function replaceCurrentUrlLanguage($currentUrl, array $languages, $replacementLanguage)
    {
        if (preg_match('/\/(' . implode('|', $languages) . ')/', $currentUrl)) {
            return preg_replace('/\/(' . implode('|', $languages) . ')/', '/' . $replacementLanguage, $currentUrl, 1);
        }

        return rtrim('/' . $replacementLanguage . $currentUrl, '/');
    }

    /**
     * @param string $locale
     *
     * @return string
     */
    protected function getLanguageFromLocale($locale): string
    {
        return substr($locale, 0, strpos($locale, '_'));
    }

    /**
     * @return string
     */
    protected function getCurrentLanguage(): string
    {
        return $this->getFactory()
            ->getStore()
            ->getCurrentLanguage();
    }
}
