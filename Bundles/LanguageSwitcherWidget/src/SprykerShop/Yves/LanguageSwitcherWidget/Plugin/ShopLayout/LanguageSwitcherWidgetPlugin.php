<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\LanguageSwitcherWidget\Plugin\ShopLayout;

use Generated\Shared\Transfer\UrlStorageTransfer;
use Spryker\Yves\Kernel\Widget\AbstractWidgetPlugin;
use SprykerShop\Yves\ShopLayoutExtension\Dependency\Plugin\LanguageSwitcherWidget\LanguageSwitcherWidgetPluginInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \SprykerShop\Yves\LanguageSwitcherWidget\LanguageSwitcherWidgetFactory getFactory()
 */
class LanguageSwitcherWidgetPlugin extends AbstractWidgetPlugin implements LanguageSwitcherWidgetPluginInterface
{
    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return void
     */
    public function initialize(Request $request): void
    {
        $currentUrlStorage = $this->getFactory()
            ->getUrlStorageClient()
            ->findUrlStorageTransferByUrl($request->getPathInfo());
        $localeUrls = [];

        if ($currentUrlStorage !== null && $currentUrlStorage->getLocaleUrls()->count() !== 0) {
            $localeUrls = (array)$currentUrlStorage->getLocaleUrls();
        }

        $this->addParameter('languages', $this->getLanguages($localeUrls, $request))
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
        return '@LanguageSwitcherWidget/views/switcher/switcher.twig';
    }

    /**
     * @param array $localeUrls
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return string[]
     */
    protected function getLanguages(array $localeUrls, Request $request): array
    {
        $locales = $this->getFactory()
            ->getStore()
            ->getLocales();

        if (!empty($localeUrls)) {
            return $this->attachLocaleUrlsFromStorageToLanguages($locales, $localeUrls, $request);
        }

        return $this->attachLocaleUrlsToLanguages($locales, $request);
    }

    /**
     * @param array $locales
     * @param array $localeUrls
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array
     */
    protected function attachLocaleUrlsFromStorageToLanguages(array $locales, array $localeUrls, Request $request): array
    {
        $languages = [];
        foreach ($locales as $locale) {
            $language = $this->getLanguageFromLocale($locale);
            foreach ($localeUrls as $localeUrl) {
                if ($localeUrl[UrlStorageTransfer::LOCALE_NAME] === $locale) {
                    $languages[$language] = $localeUrl[UrlStorageTransfer::URL] . '?' . $request->getQueryString();
                    break;
                }
            }
        }

        return $languages;
    }

    /**
     * @param array $locales
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array
     */
    protected function attachLocaleUrlsToLanguages(array $locales, Request $request): array
    {
        $currentUrl = $request->getRequestUri();
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
