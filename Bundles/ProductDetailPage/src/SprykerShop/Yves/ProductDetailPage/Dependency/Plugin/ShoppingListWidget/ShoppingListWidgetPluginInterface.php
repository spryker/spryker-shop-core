<?php

namespace SprykerShop\Yves\ProductDetailPage\Dependency\Plugin\ShoppingListWidget;

interface ShoppingListWidgetPluginInterface
{
    const NAME = 'ShoppingListWidgetPlugin';

    /**
     * @param string $sku
     * @param bool $disabled
     */
    public function initialize(string $sku, bool $disabled): void;
}