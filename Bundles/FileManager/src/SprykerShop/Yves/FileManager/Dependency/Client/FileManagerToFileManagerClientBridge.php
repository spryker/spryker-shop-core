<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\FileManager\Dependency\Client;

use Generated\Shared\Transfer\ReadFileTransfer;

class FileManagerToFileManagerClientBridge implements FileManagerToFileManagerClientInterface
{
    /**
     * @var \Spryker\Service\FileManager\FileManagerServiceInterface $fileManagerService
     */
    protected $fileManagerClient;

    /**
     * FileManagerToFileManagerClientBridge constructor.
     *
     * @param \Spryker\Client\FileManager\FileManagerClientInterface $fileManagerClient
     */
    public function __construct($fileManagerClient)
    {
        $this->fileManagerClient = $fileManagerClient;
    }

    /**
     * @param \Generated\Shared\Transfer\ReadFileTransfer $readFileTransfer
     *
     * @return \Generated\Shared\Transfer\FileManagerDataTransfer
     */
    public function readFile(ReadFileTransfer $readFileTransfer)
    {
        return $this->fileManagerClient->readFile($readFileTransfer);
    }
}
