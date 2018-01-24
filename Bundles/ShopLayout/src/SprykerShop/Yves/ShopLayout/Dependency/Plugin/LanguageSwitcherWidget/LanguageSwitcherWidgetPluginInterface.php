<?php

namespace SprykerShop\Yves\ShopLayout\Dependency\Plugin\LanguageSwitcherWidget;

interface LanguageSwitcherWidgetPluginInterface
{
    const NAME = 'LanguageSwitcherWidgetPlugin';

    /**
     * @return void
     */
    public function initialize(): void;
}