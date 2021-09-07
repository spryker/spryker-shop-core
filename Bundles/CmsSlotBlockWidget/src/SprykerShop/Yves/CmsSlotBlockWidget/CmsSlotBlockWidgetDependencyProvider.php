<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CmsSlotBlockWidget;

use Spryker\Shared\Kernel\ContainerInterface;
use Spryker\Yves\Kernel\AbstractBundleDependencyProvider;
use Spryker\Yves\Kernel\Container;
use SprykerShop\Yves\CmsSlotBlockWidget\Dependency\Client\CmsSlotBlockWidgetToCmsSlotBlockClientBridge;
use SprykerShop\Yves\CmsSlotBlockWidget\Dependency\Client\CmsSlotBlockWidgetToCmsSlotBlockStorageClientBridge;

class CmsSlotBlockWidgetDependencyProvider extends AbstractBundleDependencyProvider
{
    /**
     * @var string
     */
    public const CLIENT_CMS_SLOT_BLOCK_STORAGE = 'CLIENT_CMS_SLOT_BLOCK_STORAGE';
    /**
     * @var string
     */
    public const CLIENT_CMS_SLOT_BLOCK = 'CLIENT_CMS_SLOT_BLOCK';

    /**
     * @var string
     */
    public const SERVICE_TWIG = 'twig';

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    public function provideDependencies(Container $container): Container
    {
        $container = $this->addCmsSlotBlockStorageClient($container);
        $container = $this->addTwigEnvironment($container);
        $container = $this->addCmsSlotBlockClient($container);

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addCmsSlotBlockStorageClient(Container $container): Container
    {
        $container->set(static::CLIENT_CMS_SLOT_BLOCK_STORAGE, function (Container $container) {
            return new CmsSlotBlockWidgetToCmsSlotBlockStorageClientBridge(
                $container->getLocator()->cmsSlotBlockStorage()->client()
            );
        });

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addCmsSlotBlockClient(Container $container): Container
    {
        $container->set(static::CLIENT_CMS_SLOT_BLOCK, function (Container $container) {
            return new CmsSlotBlockWidgetToCmsSlotBlockClientBridge(
                $container->getLocator()->cmsSlotBlock()->client()
            );
        });

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addTwigEnvironment(Container $container): Container
    {
        $container->set(static::SERVICE_TWIG, function (ContainerInterface $container) {
            return $container->getApplicationService(static::SERVICE_TWIG);
        });

        return $container;
    }
}
