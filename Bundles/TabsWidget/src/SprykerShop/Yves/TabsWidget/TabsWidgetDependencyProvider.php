<?php

namespace SprykerShop\Yves\TabsWidget;

use Spryker\Yves\Kernel\AbstractBundleDependencyProvider;
use Spryker\Yves\Kernel\Container;

class TabsWidgetDependencyProvider extends AbstractBundleDependencyProvider
{
    public const PLUGINS_FULL_TEXT_SEARCH_TAB = 'full_text_search_tab_plugins';

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    public function provideDependencies(Container $container): Container
    {
        $container = $this->addFullTextSearchPlugins($container);

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    public function addFullTextSearchPlugins(Container $container): Container
    {
        $container[static::PLUGINS_FULL_TEXT_SEARCH_TAB] = function () {
            return $this->createFullTextSearchPlugins();
        };

        return $container;
    }

    /**
     * @return \SprykerShop\Yves\TabsWidgetExtension\Plugin\FullTextSearchTabPluginInterface[]
     */
    protected function createFullTextSearchPlugins(): array
    {
        return [];
    }
}
