<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CmsBlockWidget;

use Spryker\Yves\CmsContentWidget\Plugin\CmsTwigContentRendererPlugin;
use Spryker\Yves\CmsContentWidget\Plugin\CmsTwigContentRendererPluginInterface;
use Spryker\Yves\Kernel\AbstractBundleDependencyProvider;
use Spryker\Yves\Kernel\Container;
use SprykerShop\Yves\CmsBlockWidget\Dependency\Client\CmsBlockWidgetToCmsBlockStorageClientBridge;
use SprykerShop\Yves\CmsBlockWidget\Dependency\Client\CmsBlockWidgetToStoreClientBridge;

class CmsBlockWidgetDependencyProvider extends AbstractBundleDependencyProvider
{
    public const TWIG_EXTENSION_PLUGINS = 'TWIG_EXTENSION_PLUGINS';
    public const CLIENT_CMS_BLOCK_STORAGE = 'CLIENT_CMS_BLOCK_STORAGE';
    public const CLIENT_STORE = 'CLIENT_STORE';

    public const CMS_TWIG_CONTENT_RENDERER_PLUGIN = 'CMS_TWIG_CONTENT_RENDERER_PLUGIN';

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    public function provideDependencies(Container $container): Container
    {
        $container = $this->addTwigExtensionPlugins($container);
        $container = $this->addCmsBlockStorageClient($container);
        $container = $this->addStoreClient($container);
        $container = $this->addCmsTwigContentRenderePlugin($container);

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addTwigExtensionPlugins(Container $container): Container
    {
        $container->set(static::TWIG_EXTENSION_PLUGINS, function () {
            return $this->getTwigExtensionPlugins();
        });

        return $container;
    }

    /**
     * @return \Spryker\Shared\Twig\TwigExtensionInterface[]
     */
    protected function getTwigExtensionPlugins(): array
    {
        return [];
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addCmsBlockStorageClient(Container $container): Container
    {
        $container->set(static::CLIENT_CMS_BLOCK_STORAGE, function (Container $container) {
            return new CmsBlockWidgetToCmsBlockStorageClientBridge(
                $container->getLocator()->cmsBlockStorage()->client()
            );
        });

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addStoreClient(Container $container): Container
    {
        $container->set(static::CLIENT_STORE, function (Container $container) {
            return new CmsBlockWidgetToStoreClientBridge($container->getLocator()->store()->client());
        });

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    public function addCmsTwigContentRenderePlugin(Container $container): Container
    {
        $container->set(static::CMS_TWIG_CONTENT_RENDERER_PLUGIN, function () {
            return $this->getCmsTwigContentRendererPlugin();
        });

        return $container;
    }

    /**
     * @return \Spryker\Yves\CmsContentWidget\Plugin\CmsTwigContentRendererPluginInterface
     */
    protected function getCmsTwigContentRendererPlugin(): CmsTwigContentRendererPluginInterface
    {
        return new CmsTwigContentRendererPlugin();
    }
}
