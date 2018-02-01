<?php

namespace SprykerShop\Yves\ShopLayout\Dependency\Plugin\LanguageSwitcherWidget;

interface LanguageSwitcherWidgetPluginInterface
{
    const NAME = 'LanguageSwitcherWidgetPlugin';

    /**
     * @param string $currentUrl
     *
     * @return void
     */
    public function initialize($currentUrl): void;
}