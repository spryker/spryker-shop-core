<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CmsContentWidgetChartConnector;

use Spryker\Yves\Kernel\AbstractFactory;
use Spryker\Yves\Kernel\Widget\WidgetCollection;
use Spryker\Yves\Kernel\Widget\WidgetContainerInterface;
use Spryker\Yves\Kernel\Widget\WidgetContainerRegistry;

class CmsContentWidgetChartConnectorFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Yves\Kernel\Widget\WidgetContainerRegistry
     */
    public function createWidgetContainerRegistry(): WidgetContainerRegistry
    {
        return new WidgetContainerRegistry();
    }

    /**
     * @return \Spryker\Yves\Kernel\Widget\WidgetContainerInterface
     */
    public function createCmsChartContentWidgetCollection(): WidgetContainerInterface
    {
        return new WidgetCollection($this->getCmsChartContentWidgetPlugins());
    }

    /**
     * @return string[]
     */
    protected function getCmsChartContentWidgetPlugins(): array
    {
        return $this->getProvidedDependency(CmsContentWidgetChartConnectorDependencyProvider::PLUGIN_CMS_CHART_CONTENT_WIDGETS);
    }
}
