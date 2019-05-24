<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ContentFileWidget\Dependency\Client;

class ContentFileWidgetToFileManagerStorageClientBridge implements ContentFileWidgetToFileManagerStorageClientInterface
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
     * @param int $idFile
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\FileStorageDataTransfer|null
     */
    public function findFileById(int $idFile, string $localeName)
    {
        return $this->fileManagerStorageClient->findFileById($idFile, $localeName);
    }
}
