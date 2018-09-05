<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CmsSearchPage;

use Spryker\Yves\Kernel\AbstractBundleDependencyProvider;
use Spryker\Yves\Kernel\Container;
use SprykerShop\Yves\CmsSearchPage\Dependency\Client\CmsSearchPageToCmsSearchPageClientBridge;

class CmsSearchPageDependencyProvider extends AbstractBundleDependencyProvider
{
    public const CLIENT_CMS_SEARCH_PAGE = 'CLIENT_CMS_SEARCH_PAGE';
    public const PLUGIN_CMS_SEARCH_PAGE_WIDGETS = 'PLUGIN_CMS_SEARCH_PAGE_WIDGETS';

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    public function provideDependencies(Container $container): Container
    {
        $container = $this->addCmsSearchPageClient($container);
        $container = $this->addCmsSearchPageWidgetPlugins($container);

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addCmsSearchPageClient(Container $container): Container
    {
        $container[static::CLIENT_CMS_SEARCH_PAGE] = function (Container $container) {
            return new CmsSearchPageToCmsSearchPageClientBridge($container->getLocator()->cmsPageSearch()->client());
        };

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addCmsSearchPageWidgetPlugins(Container $container): Container
    {
        $container[static::PLUGIN_CMS_SEARCH_PAGE_WIDGETS] = function () {
            return $this->getCmsSearchPageWidgetPlugins();
        };

        return $container;
    }

    /**
     * @return string[]
     */
    protected function getCmsSearchPageWidgetPlugins(): array
    {
        return [];
    }
}
