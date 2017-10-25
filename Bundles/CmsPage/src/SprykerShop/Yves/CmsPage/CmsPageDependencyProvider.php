<?php

/**
 * This file is part of the Spryker Demoshop.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerShop\Yves\CmsPage;

use Spryker\Shared\Kernel\Store;
use Spryker\Yves\CmsContentWidget\Plugin\CmsTwigContentRendererPlugin;
use Spryker\Yves\Kernel\AbstractBundleDependencyProvider;
use Spryker\Yves\Kernel\Container;

class CmsPageDependencyProvider extends AbstractBundleDependencyProvider
{

    const CMS_TWIG_CONTENT_RENDERER_PLUGIN = 'CMS_TWIG_CONTENT_RENDERER_PLUGIN';
    const CLIENT_CMS = 'CLIENT_CMS';
    const CLIENT_CUSTOMER = 'CLIENT_CUSTOMER';
    const CLIENT_CMS_COLLECTOR = 'CLIENT_CMS_COLLECTOR';
    const STORE = 'STORE';

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    public function provideDependencies(Container $container)
    {
        $container = $this->addCmsTwigContentRendererPlugin($container);
        $container = $this->addCmsClient($container);
        $container = $this->addCustomerClient($container);
        $container = $this->addCmsCollectorClient($container);
        $container = $this->addStore($container);

        return $container;
    }

    /**
     * @param Container $container
     *
     * @return Container
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
            return $container->getLocator()->cms()->client();
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
            return $container->getLocator()->customer()->client();
        };

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addCmsCollectorClient(Container $container)
    {
        $container[static::CLIENT_CMS_COLLECTOR] = function (Container $container) {
            return $container->getLocator()->cmsCollector()->client();
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
