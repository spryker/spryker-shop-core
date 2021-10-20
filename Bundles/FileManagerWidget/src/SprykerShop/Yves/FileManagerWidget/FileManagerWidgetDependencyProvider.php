<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\FileManagerWidget;

use Spryker\Yves\Kernel\AbstractBundleDependencyProvider;
use Spryker\Yves\Kernel\Container;
use SprykerShop\Yves\FileManagerWidget\Dependency\Client\FileManagerWidgetToFileManagerStorageClientBridge;
use SprykerShop\Yves\FileManagerWidget\Dependency\Service\FileManagerWidgetToFileManagerServiceBridge;

class FileManagerWidgetDependencyProvider extends AbstractBundleDependencyProvider
{
    /**
     * @var string
     */
    public const CLIENT_FILE_MANAGER_STORAGE = 'CLIENT_FILE_MANAGER_STORAGE';

    /**
     * @var string
     */
    public const SERVICE_FILE_MANAGER = 'SERVICE_FILE_MANAGER';

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    public function provideDependencies(Container $container)
    {
        $container = parent::provideDependencies($container);
        $container = $this->addFileManagerService($container);
        $container = $this->addFileManagerStorageClient($container);

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addFileManagerService(Container $container)
    {
        $container->set(static::SERVICE_FILE_MANAGER, function (Container $container) {
            return new FileManagerWidgetToFileManagerServiceBridge(
                $container->getLocator()->fileManager()->service(),
            );
        });

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addFileManagerStorageClient(Container $container)
    {
        $container->set(static::CLIENT_FILE_MANAGER_STORAGE, function (Container $container) {
            return new FileManagerWidgetToFileManagerStorageClientBridge(
                $container->getLocator()->fileManagerStorage()->client(),
            );
        });

        return $container;
    }
}
