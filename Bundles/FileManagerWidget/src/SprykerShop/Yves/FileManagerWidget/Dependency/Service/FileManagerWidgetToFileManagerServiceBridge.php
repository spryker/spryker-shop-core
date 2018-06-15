<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\FileManagerWidget\Dependency\Service;

use Generated\Shared\Transfer\FileManagerDataTransfer;

class FileManagerWidgetToFileManagerServiceBridge implements FileManagerWidgetToFileManagerServiceInterface
{
    /**
     * @var \Spryker\Service\FileManager\FileManagerServiceInterface $fileManagerService
     */
    protected $fileManagerService;

    /**
     * @param \Spryker\Service\FileManager\FileManagerServiceInterface $fileManagerService
     */
    public function __construct($fileManagerService)
    {
        $this->fileManagerService = $fileManagerService;
    }

    /**
     * @param string $fileName
     *
     * @return \Generated\Shared\Transfer\FileManagerDataTransfer
     */
    public function read(string $fileName): FileManagerDataTransfer
    {
        return $this->fileManagerService->read($fileName);
    }

    /**
     * @param string $fileName
     *
     * @return mixed
     */
    public function readStream(string $fileName)
    {
        return $this->fileManagerService->readStream($fileName);
    }
}
