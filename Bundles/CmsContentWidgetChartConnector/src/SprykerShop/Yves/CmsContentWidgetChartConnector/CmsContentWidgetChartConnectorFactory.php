<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CmsContentWidgetChartConnector;

use Spryker\Yves\CmsContentWidgetChartConnector\CmsContentWidgetChartConnectorFactory as SprykerCmsContentWidgetChartConnectorFactory;
use Spryker\Yves\Kernel\Widget\WidgetCollection;
use Spryker\Yves\Kernel\Widget\WidgetContainerRegistry;

class CmsContentWidgetChartConnectorFactory extends SprykerCmsContentWidgetChartConnectorFactory
{
    /**
     * @return \Spryker\Yves\Kernel\Widget\WidgetContainerRegistry
     */
    public function createWidgetContainerRegistry()
    {
        return new WidgetContainerRegistry();
    }

    /**
     * @return \Spryker\Yves\Kernel\Widget\WidgetContainerInterface
     */
    public function createCmsChartContentWidgetCollection()
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
