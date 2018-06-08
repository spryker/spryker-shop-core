<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\FileManager;

use Spryker\Yves\Kernel\AbstractBundleDependencyProvider;
use Spryker\Yves\Kernel\Container;
use SprykerShop\Yves\FileManager\Dependency\Client\FileManagerToFileManagerClientBridge;
use SprykerShop\Yves\FileManager\Dependency\Service\FileManagerToFileManagerServiceBridge;

class FileManagerDependencyProvider extends AbstractBundleDependencyProvider
{
    const FILE_MANAGER_SERVICE = 'FILE_MANAGER_SERVICE';
    const FILE_MANAGER_CLIENT = 'FILE_MANAGER_CLIENT';

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    public function provideDependencies(Container $container)
    {
        $container = parent::provideDependencies($container);
        $container = $this->addFileManagerService($container);
        $container = $this->addFileManagerClient($container);

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addFileManagerService(Container $container)
    {
        $container[static::FILE_MANAGER_SERVICE] = function (Container $container) {
            return new FileManagerToFileManagerServiceBridge(
                $container->getLocator()->fileManager()->service()
            );
        };

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addFileManagerClient(Container $container)
    {
        $container[static::FILE_MANAGER_CLIENT] = function (Container $container) {
            return new FileManagerToFileManagerClientBridge(
                $container->getLocator()->fileManager()->client()
            );
        };

        return $container;
    }
}
