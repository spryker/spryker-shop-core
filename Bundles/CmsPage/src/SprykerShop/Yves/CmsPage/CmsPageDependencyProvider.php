<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CmsPage;

use Spryker\Shared\Kernel\Store;
use Spryker\Yves\CmsContentWidget\Plugin\CmsTwigContentRendererPlugin;
use Spryker\Yves\Kernel\AbstractBundleDependencyProvider;
use Spryker\Yves\Kernel\Container;
use SprykerShop\Yves\CmsPage\Dependency\Client\CmsPageToCmsClientBridge;
use SprykerShop\Yves\CmsPage\Dependency\Client\CmsPageToCmsStorageClientBridge;
use SprykerShop\Yves\CmsPage\Dependency\Client\CmsPageToCustomerClientBridge;

class CmsPageDependencyProvider extends AbstractBundleDependencyProvider
{
    public const CMS_TWIG_CONTENT_RENDERER_PLUGIN = 'CMS_TWIG_CONTENT_RENDERER_PLUGIN';
    public const CLIENT_CMS = 'CLIENT_CMS';
    public const CLIENT_CUSTOMER = 'CLIENT_CUSTOMER';
    public const STORE = 'STORE';
    public const CLIENT_CMS_STORAGE = 'CLIENT_CMS_STORAGE';

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    public function provideDependencies(Container $container)
    {
        $container = $this->addCmsTwigContentRendererPlugin($container);
        $container = $this->addCmsClient($container);
        $container = $this->addCmsStorageClient($container);
        $container = $this->addCustomerClient($container);
        $container = $this->addStore($container);

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addCmsTwigContentRendererPlugin(Container $container): Container
    {
        $container[static::CMS_TWIG_CONTENT_RENDERER_PLUGIN] = function (Container $container) {
            return new CmsTwigContentRendererPlugin();
        };

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addCmsClient(Container $container)
    {
        $container[static::CLIENT_CMS] = function (Container $container) {
            return new CmsPageToCmsClientBridge($container->getLocator()->cms()->client());
        };

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addCmsStorageClient(Container $container)
    {
        $container[static::CLIENT_CMS_STORAGE] = function (Container $container) {
            return new CmsPageToCmsStorageClientBridge($container->getLocator()->cmsStorage()->client());
        };

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addCustomerClient(Container $container)
    {
        $container[static::CLIENT_CUSTOMER] = function (Container $container) {
            return new CmsPageToCustomerClientBridge($container->getLocator()->customer()->client());
        };

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addStore(Container $container)
    {
        $container[static::STORE] = function (Container $container) {
            return Store::getInstance();
        };

        return $container;
    }
}
