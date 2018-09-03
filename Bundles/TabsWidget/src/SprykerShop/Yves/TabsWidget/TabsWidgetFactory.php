<?php

namespace SprykerShop\Yves\TabsWidget;

use Spryker\Yves\Kernel\AbstractFactory;

class TabsWidgetFactory extends AbstractFactory
{
    /**
     * @return \SprykerShop\Yves\TabsWidgetExtension\Plugin\FullTextSearchTabPluginInterface[]
     *
     * @throws \Spryker\Yves\Kernel\Exception\Container\ContainerKeyNotFoundException
     */
    public function getFullTextSearchPlugins(): array
    {
        return $this->getProvidedDependency(TabsWidgetDependencyProvider::PLUGINS_FULL_TEXT_SEARCH_TAB);
    }
}
