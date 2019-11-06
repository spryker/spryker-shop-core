<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CmsSlotBlockWidget;

use Spryker\Yves\Kernel\AbstractBundleDependencyProvider;
use Spryker\Yves\Kernel\Container;
use Spryker\Yves\Kernel\Plugin\Pimple;
use SprykerShop\Yves\CmsSlotBlockWidget\Dependency\Client\CmsSlotBlockWidgetToCmsSlotBlockClientBridge;
use SprykerShop\Yves\CmsSlotBlockWidget\Dependency\Client\CmsSlotBlockWidgetToCmsSlotBlockStorageClientBridge;
use Twig\Environment;

class CmsSlotBlockWidgetDependencyProvider extends AbstractBundleDependencyProvider
{
    public const CLIENT_CMS_SLOT_BLOCK_STORAGE = 'CLIENT_CMS_SLOT_BLOCK_STORAGE';
    public const CLIENT_CMS_SLOT_BLOCK = 'CLIENT_CMS_SLOT_BLOCK';

    public const TWIG_ENVIRONMENT = 'TWIG ENVIRONMENT';

    protected const SERVICE_TWIG = 'twig';

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
     *
     * TODO: inject client to bridge
     */
    protected function addCmsSlotBlockStorageClient(Container $container): Container
    {
        $container->set(static::CLIENT_CMS_SLOT_BLOCK_STORAGE, function (Container $container) {
            return new CmsSlotBlockWidgetToCmsSlotBlockStorageClientBridge();
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
        $container[static::TWIG_ENVIRONMENT] = function (Container $container) {
            return $this->getTwigEnvironment();
        };

        return $container;
    }

    /**
     * @return \Twig\Environment
     */
    protected function getTwigEnvironment(): Environment
    {
        $pimplePlugin = new Pimple();

        return $pimplePlugin->getApplication()[static::SERVICE_TWIG];
    }
}
