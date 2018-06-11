<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\FileManagerWidget;

use Spryker\Yves\Kernel\AbstractFactory;

class FileManagerWidgetFactory extends AbstractFactory
{
    /**
     * @return \SprykerShop\Yves\FileManagerWidget\Dependency\Service\FileManagerWidgetToFileManagerServiceInterface
     */
    public function getFileManagerService()
    {
        return $this->getProvidedDependency(FileManagerWidgetDependencyProvider::FILE_MANAGER_SERVICE);
    }

    /**
     * @return \SprykerShop\Yves\FileManagerWidget\Dependency\Client\FileManagerWidgetToFileManagerClientInterface
     */
    public function getFileManagerClient()
    {
        return $this->getProvidedDependency(FileManagerWidgetDependencyProvider::FILE_MANAGER_CLIENT);
    }
}
