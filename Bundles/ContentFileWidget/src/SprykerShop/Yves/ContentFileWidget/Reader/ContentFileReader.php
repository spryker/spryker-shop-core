<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ContentFileWidget\Reader;

use Generated\Shared\Transfer\FileStorageDataTransfer;
use SprykerShop\Yves\ContentFileWidget\Dependency\Client\ContentFileWidgetToContentFileClientInterface;
use SprykerShop\Yves\ContentFileWidget\Dependency\Client\ContentFileWidgetToFileManagerStorageClientInterface;

class ContentFileReader implements ContentFileReaderInterface
{
    /**
     * @var \SprykerShop\Yves\ContentFileWidget\Dependency\Client\ContentFileWidgetToContentFileClientInterface
     */
    protected $contentFileClient;

    /**
     * @var \SprykerShop\Yves\ContentFileWidget\Dependency\Client\ContentFileWidgetToFileManagerStorageClientInterface
     */
    protected $fileManagerStorageClient;

    /**
     * @param \SprykerShop\Yves\ContentFileWidget\Dependency\Client\ContentFileWidgetToContentFileClientInterface $contentFileClient
     * @param \SprykerShop\Yves\ContentFileWidget\Dependency\Client\ContentFileWidgetToFileManagerStorageClientInterface $fileManagerStorageClient
     */
    public function __construct(
        ContentFileWidgetToContentFileClientInterface $contentFileClient,
        ContentFileWidgetToFileManagerStorageClientInterface $fileManagerStorageClient
    ) {
        $this->contentFileClient = $contentFileClient;
        $this->fileManagerStorageClient = $fileManagerStorageClient;
    }

    /**
     * @param int $idContent
     * @param string $localeName
     *
     * @return array|null
     */
    public function findFileCollection(int $idContent, string $localeName): ?array
    {
        $contentFileListTypeTransfer = $this->contentFileClient->executeContentFileListTypeById($idContent, $localeName);

        if ($contentFileListTypeTransfer === null) {
            return null;
        }

        $fileViewCollection = [];

        foreach ($contentFileListTypeTransfer->getFileIds() as $fileId) {
            $fileStorageDataTransfer = $this->fileManagerStorageClient->findFileById($fileId, $localeName);

            if (!$fileStorageDataTransfer) {
                continue;
            }

            $fileStorageDataTransfer = $this->calculateFileSize($fileStorageDataTransfer);
            $fileStorageDataTransfer = $this->getFileDisplayType($fileStorageDataTransfer);
            $fileViewCollection[] = $fileStorageDataTransfer;
        }

        return $fileViewCollection;
    }

    protected function calculateFileSize(FileStorageDataTransfer $fileStorageDataTransfer): FileStorageDataTransfer
    {
        $labels = ['B', 'Kb', 'MB', 'GB', 'TB', 'PB'];
        $labelKey = floor(log($fileStorageDataTransfer->getSize(), 1024));
        $size = number_format($fileStorageDataTransfer->getSize()/(1024 ** $labelKey), 2, '.', ',');

        return sprintf('%s %s', $size, $labels[(int)$labelKey]);
    }

    protected function getFileDisplayType(FileStorageDataTransfer $fileStorageDataTransfer): FileStorageDataTransfer
    {

    }
}
