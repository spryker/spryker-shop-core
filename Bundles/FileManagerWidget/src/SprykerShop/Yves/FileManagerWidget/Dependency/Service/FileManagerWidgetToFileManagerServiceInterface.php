<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\FileManagerWidget\Dependency\Service;

use Generated\Shared\Transfer\FileManagerDataTransfer;

interface FileManagerWidgetToFileManagerServiceInterface
{
    /**
     * @param string $fileName
     *
     * @throws \Spryker\Service\FileSystemExtension\Dependency\Exception\FileSystemReadException
     *
     * @return \Generated\Shared\Transfer\FileManagerDataTransfer
     */
    public function read(string $fileName): FileManagerDataTransfer;

    /**
     * @param string $fileName
     *
     * @throws \Spryker\Service\FileSystemExtension\Dependency\Exception\FileSystemStreamException
     *
     * @return mixed
     */
    public function readStream(string $fileName);
}
