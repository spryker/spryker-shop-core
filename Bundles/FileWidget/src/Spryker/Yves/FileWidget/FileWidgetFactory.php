<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\FileWidget;

use Spryker\Yves\Kernel\AbstractFactory;

class FileWidgetFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Client\FileManagerStorage\FileManagerStorageClientInterface
     */
    public function getFileManagerClient()
    {
        return $this->getProvidedDependency(FileWidgetDependencyProvider::FILE_CLIENT);
    }
}
