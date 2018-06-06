<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\FileManager;

use Spryker\Yves\Kernel\AbstractFactory;

class FileManagerFactory extends AbstractFactory
{
    /**
     * @return \SprykerShop\Yves\FileManager\Dependency\Service\FileManagerToFileManagerServiceInterface
     */
    public function getFileManagerService()
    {
        return $this->getProvidedDependency(FileManagerDependencyProvider::FILE_MANAGER_SERVICE);
    }
}
