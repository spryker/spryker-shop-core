<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ContentFileWidget\Reader;

use SprykerShop\Yves\ContentFileWidget\Dependency\Client\ContentFileWidgetToContentFileClientInterface;
use SprykerShop\Yves\ContentFileWidget\Dependency\Client\ContentFileWidgetToFileManagerStorageClientInterface;
use SprykerShop\Yves\ContentFileWidget\Expander\FileStorageDataExpanderInterface;

class ContentFileReader implements ContentFileReaderInterface
{
    protected const LABEL_FILE_SIZES = ['B', 'Kb', 'MB', 'GB', 'TB', 'PB'];

    /**
     * @var \SprykerShop\Yves\ContentFileWidget\Dependency\Client\ContentFileWidgetToContentFileClientInterface
     */
    protected $contentFileClient;

    /**
     * @var \SprykerShop\Yves\ContentFileWidget\Dependency\Client\ContentFileWidgetToFileManagerStorageClientInterface
     */
    protected $fileManagerStorageClient;

    /**
     * @var \SprykerShop\Yves\ContentFileWidget\Expander\FileStorageDataExpanderInterface
     */
    protected $fileStorageDataExpander;

    /**
     * @param \SprykerShop\Yves\ContentFileWidget\Dependency\Client\ContentFileWidgetToContentFileClientInterface $contentFileClient
     * @param \SprykerShop\Yves\ContentFileWidget\Dependency\Client\ContentFileWidgetToFileManagerStorageClientInterface $fileManagerStorageClient
     * @param \SprykerShop\Yves\ContentFileWidget\Expander\FileStorageDataExpanderInterface $fileStorageDataExpander
     */
    public function __construct(
        ContentFileWidgetToContentFileClientInterface $contentFileClient,
        ContentFileWidgetToFileManagerStorageClientInterface $fileManagerStorageClient,
        FileStorageDataExpanderInterface $fileStorageDataExpander
    ) {
        $this->contentFileClient = $contentFileClient;
        $this->fileManagerStorageClient = $fileManagerStorageClient;
        $this->fileStorageDataExpander = $fileStorageDataExpander;
    }

    /**
     * @param int $idContent
     * @param string $localeName
     *
     * @return array|null
     */
    public function getFileCollection(int $idContent, string $localeName): ?array
    {
        $contentFileListTypeTransfer = $this->contentFileClient->executeFileListTypeById($idContent, $localeName);
        $fileViewCollection = [];

        if ($contentFileListTypeTransfer === null) {
            return null;
        }

        foreach ($contentFileListTypeTransfer->getFileIds() as $fileId) {
            $fileStorageDataTransfer = $this->fileManagerStorageClient->findFileById($fileId, $localeName);

            if (!$fileStorageDataTransfer) {
                continue;
            }

            $fileDisplaySize = $this->getFileDisplaySize($fileStorageDataTransfer->getSize());
            $fileIconName = $this->fileStorageDataExpander->getIconName($fileStorageDataTransfer);

            $fileViewCollection[] = $fileStorageDataTransfer->setDisplaySize($fileDisplaySize)
                ->setIconName($fileIconName);
        }

        return $fileViewCollection;
    }

    /**
     * @param int $fileSize
     *
     * @return string
     */
    protected function getFileDisplaySize(int $fileSize): string
    {
        $power = floor(log($fileSize, 1024));
        $calculatedSize = number_format($fileSize / (1024 ** $power), 1);

        return sprintf('%s %s', $calculatedSize, static::LABEL_FILE_SIZES[(int)$power]);
    }
}
