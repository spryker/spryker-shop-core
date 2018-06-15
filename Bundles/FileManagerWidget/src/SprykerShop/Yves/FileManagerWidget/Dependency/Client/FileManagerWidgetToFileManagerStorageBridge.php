<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\FileManagerWidget\Dependency\Client;

use Generated\Shared\Transfer\FileManagerStorageTransfer;

class FileManagerWidgetToFileManagerStorageBridge implements FileManagerWidgetToFileManagerStorageInterface
{
    /**
     * @var \Spryker\Client\FileManagerStorage\FileManagerStorageClientInterface
     */
    protected $fileManagerStorageClient;

    /**
     * @param \Spryker\Client\FileManagerStorage\FileManagerStorageClientInterface $fileManagerStorageClient
     */
    public function __construct($fileManagerStorageClient)
    {
        $this->fileManagerStorageClient = $fileManagerStorageClient;
    }

    /**
     * @param int $fileId
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\FileManagerStorageTransfer|null
     */
    public function findFileById(int $fileId, string $localeName): ?FileManagerStorageTransfer
    {
        return $this->fileManagerStorageClient->findFileById($fileId, $localeName);
    }
}
