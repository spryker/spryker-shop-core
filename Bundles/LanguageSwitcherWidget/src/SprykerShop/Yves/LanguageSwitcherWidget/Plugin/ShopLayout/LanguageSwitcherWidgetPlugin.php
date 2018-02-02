<?php

namespace SprykerShop\Yves\LanguageSwitcherWidget\Plugin\ShopLayout;

use Generated\Shared\Transfer\UrlTransfer;
use Spryker\Yves\Kernel\Widget\AbstractWidgetPlugin;
use SprykerShop\Yves\ShopLayout\Dependency\Plugin\LanguageSwitcherWidget\LanguageSwitcherWidgetPluginInterface;
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
     *
     * @throws \Spryker\Yves\Kernel\Exception\Container\ContainerKeyNotFoundException
     */
    public function initialize(Request $request): void
    {
        $currentUrlStorage = $this->getFactory()
            ->getUrlStorageClient()
            ->getUrlData($request->getPathInfo());
        $localeUrls = [];

        if(isset($currentUrlStorage[UrlTransfer::LOCALE_URLS])) {
            $localeUrls = $currentUrlStorage[UrlTransfer::LOCALE_URLS];
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
        return '@LanguageSwitcherWidget/_partials/_language_switcher.twig';
    }

    /**
     * @param array $localeUrls
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return string[]
     *
     * @throws \Spryker\Yves\Kernel\Exception\Container\ContainerKeyNotFoundException
     */
    protected function getLanguages(array $localeUrls, $request): array
    {
        $locales = $this->getFactory()
            ->getStore()
            ->getLocales();

        return $this->attachUrlsToLanguages($locales, $localeUrls, $request);
    }

    /**
     * @param array $locales
     * @param array $localeUrls
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array
     */
    protected function attachUrlsToLanguages(array $locales, array $localeUrls, $request): array
    {
        $currentUrl = $request->getRequestUri();
        $languages = [];
        foreach ($locales as $locale) {
            $language = substr($locale, 0, strpos($locale, '_'));
            if(empty($localeUrls)) {
                $replacementCounts = 0;
                $languages[$language] = str_replace(array_keys($locales), $language, $currentUrl, $replacementCounts);
                if($replacementCounts === 0) {
                    $languages[$language] = rtrim('/' . $language . '/' . $currentUrl, '/');
                }
            }

            foreach($localeUrls as $localeUrl) {
                if($localeUrl[UrlTransfer::LOCALE_NAME] === $locale) {
                    $languages[$language] = $localeUrl[UrlTransfer::URL] . '?' . $request->getQueryString();
                    break;
                }
            }
        }

        return $languages;
    }

    /**
     * @return string
     *
     * @throws \Spryker\Yves\Kernel\Exception\Container\ContainerKeyNotFoundException
     */
    protected function getCurrentLanguage(): string
    {
        return $this->getFactory()
            ->getStore()
            ->getCurrentLanguage();
    }
}
