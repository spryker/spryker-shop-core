<?php

namespace SprykerShop\Yves\ShopLayout\Dependency\Plugin\LanguageSwitcherWidget;

interface LanguageSwitcherWidgetPluginInterface
{
    const NAME = 'LanguageSwitcherWidgetPlugin';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return void
     */
    public function initialize($request): void;
}