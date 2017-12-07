<?php

/**
 * This file is part of the Spryker Demoshop.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerShop\Yves\CmsContentWidgetProductConnector;

use Spryker\Yves\CmsContentWidgetProductConnector\CmsContentWidgetProductConnectorDependencyProvider as SprykerCmsContentWidgetProductConnectorDependencyProvider;
use Spryker\Yves\Kernel\Container;
use Spryker\Yves\Kernel\Plugin\Pimple;

class CmsContentWidgetProductConnectorDependencyProvider extends SprykerCmsContentWidgetProductConnectorDependencyProvider
{
    const APPLICATION = 'APPLICATION';
    const PLUGIN_CMS_PRODUCT_CONTENT_WIDGETS = 'PLUGIN_CMS_PRODUCT_CONTENT_WIDGETS';

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    public function provideDependencies(Container $container)
    {
        $container = parent::provideDependencies($container);
        $container = $this->addApplication($container);
        $container = $this->addCmsProductContentWidgetPlugins($container);

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addApplication(Container $container): Container
    {
        $container[static::APPLICATION] = function (Container $container) {
            return (new Pimple())->getApplication();
        };

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addCmsProductContentWidgetPlugins(Container $container): Container
    {
        $container[static::PLUGIN_CMS_PRODUCT_CONTENT_WIDGETS] = function (Container $container) {
            return $this->getCmsProductContentWidgetPlugins();
        };

        return $container;
    }

    /**
     * @return string[]
     */
    protected function getCmsProductContentWidgetPlugins()
    {
        return [];
    }
}
