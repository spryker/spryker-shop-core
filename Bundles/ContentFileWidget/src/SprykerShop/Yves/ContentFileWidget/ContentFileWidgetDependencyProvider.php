<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ContentFileWidget;

use Spryker\Yves\Kernel\AbstractBundleDependencyProvider;
use Spryker\Yves\Kernel\Container;
use SprykerShop\Yves\ContentFileWidget\Dependency\Client\ContentFileWidgetToContentFileClientBridge;
use SprykerShop\Yves\ContentFileWidget\Dependency\Client\ContentFileWidgetToFileManagerStorageClientBridge;

/**
 * @method \SprykerShop\Yves\ContentFileWidget\ContentFileWidgetConfig getConfig()
 */
class ContentFileWidgetDependencyProvider extends AbstractBundleDependencyProvider
{
    /**
     * @var string
     */
    public const CLIENT_CONTENT_FILE = 'CLIENT_CONTENT_FILE';

    /**
     * @var string
     */
    public const CLIENT_FILE_MANAGER_STORAGE = 'CLIENT_FILE_MANAGER_STORAGE';

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    public function provideDependencies(Container $container): Container
    {
        $container = parent::provideDependencies($container);
        $container = $this->addContentFileClient($container);
        $container = $this->addFileManagerStorageClient($container);

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    public function addContentFileClient(Container $container): Container
    {
        $container->set(static::CLIENT_CONTENT_FILE, function (Container $container) {
            return new ContentFileWidgetToContentFileClientBridge($container->getLocator()->contentFile()->client());
        });

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    public function addFileManagerStorageClient(Container $container): Container
    {
        $container->set(static::CLIENT_FILE_MANAGER_STORAGE, function (Container $container) {
            return new ContentFileWidgetToFileManagerStorageClientBridge($container->getLocator()->fileManagerStorage()->client());
        });

        return $container;
    }
}
