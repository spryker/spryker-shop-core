<?php

namespace SprykerShop\Yves\LanguageSwitcherWidget\Plugin\ShopLayout;

use Generated\Shared\Transfer\UrlTransfer;
use Spryker\Yves\Kernel\Widget\AbstractWidgetPlugin;
use SprykerShop\Yves\ShopLayout\Dependency\Plugin\LanguageSwitcherWidget\LanguageSwitcherWidgetPluginInterface;

/**
 * @method \SprykerShop\Yves\LanguageSwitcherWidget\LanguageSwitcherWidgetFactory getFactory()
 */
class LanguageSwitcherWidgetPlugin extends AbstractWidgetPlugin implements LanguageSwitcherWidgetPluginInterface
{
    /**
     * @param string $currentUrl
     *
     * @return void
     *
     * @throws \Spryker\Yves\Kernel\Exception\Container\ContainerKeyNotFoundException
     */
    public function initialize($currentUrl): void
    {
        $currentUrlStorage = $this->getFactory()->getUrlStorageClient()->getUrlData($currentUrl);
        $localeUrls = [];

        if(isset($currentUrlStorage[UrlTransfer::LOCALE_URLS])) {
            $localeUrls = $currentUrlStorage[UrlTransfer::LOCALE_URLS];
        }

        $this->addParameter('languages', $this->getLanguages($localeUrls, $currentUrl))
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
     * @param string $currentUrl
     *
     * @return string[]
     *
     * @throws \Spryker\Yves\Kernel\Exception\Container\ContainerKeyNotFoundException
     */
    protected function getLanguages(array $localeUrls, $currentUrl): array
    {
        $languages = [];
        $locales = $this->getFactory()
            ->getStore()
            ->getLocales();

        foreach ($locales as $locale) {
            $language = substr($locale, 0, strpos($locale, '_'));
            if(empty($localeUrls)) {
                $languages[$language] = str_replace(array_keys($locales), $language, $currentUrl);
            }

            foreach($localeUrls as $localeUrl) {
                if($localeUrl[UrlTransfer::LOCALE_NAME] === $locale) {
                    $languages[$language] = $localeUrl[UrlTransfer::URL];
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
