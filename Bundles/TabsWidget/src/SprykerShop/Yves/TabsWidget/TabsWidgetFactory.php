<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\TabsWidget;

use Spryker\Yves\Kernel\AbstractFactory;

class TabsWidgetFactory extends AbstractFactory
{
    /**
     * @return \SprykerShop\Yves\TabsWidgetExtension\Plugin\FullTextSearchTabPluginInterface[]
     */
    public function getFullTextSearchTabPlugins(): array
    {
        return $this->getProvidedDependency(TabsWidgetDependencyProvider::PLUGINS_FULL_TEXT_SEARCH_TAB);
    }
}
