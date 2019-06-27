<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ContentFileWidget\Reader;

use Generated\Shared\Transfer\ContentFileListTypeTransfer;
use Generated\Shared\Transfer\FileStorageDataTransfer;
use SprykerShop\Yves\ContentFileWidget\Dependency\Client\ContentFileWidgetToFileManagerStorageClientInterface;

class ContentFileReader implements ContentFileReaderInterface
{
    /**
     * @var \SprykerShop\Yves\ContentFileWidget\Dependency\Client\ContentFileWidgetToFileManagerStorageClientInterface
     */
    protected $fileManagerStorageClient;

    /**
     * @var \SprykerShop\Yves\ContentFileWidget\Expander\FileStorageDataExpanderInterface[]
     */
    protected $fileStorageDataExpanders;

    /**
     * @param \SprykerShop\Yves\ContentFileWidget\Dependency\Client\ContentFileWidgetToFileManagerStorageClientInterface $fileManagerStorageClient
     * @param \SprykerShop\Yves\ContentFileWidget\Expander\FileStorageDataExpanderInterface[] $fileStorageDataExpanders
     */
    public function __construct(
        ContentFileWidgetToFileManagerStorageClientInterface $fileManagerStorageClient,
        array $fileStorageDataExpanders
    ) {
        $this->fileManagerStorageClient = $fileManagerStorageClient;
        $this->fileStorageDataExpanders = $fileStorageDataExpanders;
    }

    /**
     * @param \Generated\Shared\Transfer\ContentFileListTypeTransfer $contentFileListTypeTransfer
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\FileStorageDataTransfer[]
     */
    public function getFileCollection(ContentFileListTypeTransfer $contentFileListTypeTransfer, string $localeName): array
    {
        $fileViewCollection = [];

        foreach ($contentFileListTypeTransfer->getFileIds() as $fileId) {
            $fileStorageDataTransfer = $this->fileManagerStorageClient->findFileById($fileId, $localeName);

            if (!$fileStorageDataTransfer) {
                continue;
            }

            $fileViewCollection[] = $this->expandFileStorageDataTransfer($fileStorageDataTransfer);
        }

        return $fileViewCollection;
    }

    /**
     * @param \Generated\Shared\Transfer\FileStorageDataTransfer $fileStorageDataTransfer
     *
     * @return \Generated\Shared\Transfer\FileStorageDataTransfer
     */
    protected function expandFileStorageDataTransfer(FileStorageDataTransfer $fileStorageDataTransfer): FileStorageDataTransfer
    {
        foreach ($this->fileStorageDataExpanders as $fileStorageDataExpander) {
            /**
             * @var \SprykerShop\Yves\ContentFileWidget\Expander\FileStorageDataExpanderInterface|\SprykerShop\Yves\ContentFileWidget\Expander\IconNameFileStorageDataExpander $fileStorageDataExpander
             */
            $fileStorageDataTransfer = $fileStorageDataExpander->expand($fileStorageDataTransfer);
        }

        return $fileStorageDataTransfer;
    }
}
