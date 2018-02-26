<?php

namespace SprykerShop\Yves\ShopLayoutExtension\Dependency\Plugin\LanguageSwitcherWidget;

use Symfony\Component\HttpFoundation\Request;

interface LanguageSwitcherWidgetPluginInterface
{
    const NAME = 'LanguageSwitcherWidgetPlugin';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return void
     */
    public function initialize(Request $request): void;
}