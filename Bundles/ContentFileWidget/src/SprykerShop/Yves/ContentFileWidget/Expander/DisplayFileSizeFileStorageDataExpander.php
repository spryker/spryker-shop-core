<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ContentFileWidget\Expander;

use Generated\Shared\Transfer\FileStorageDataTransfer;

class DisplayFileSizeFileStorageDataExpander implements FileStorageDataExpanderInterface
{
    protected const LABEL_FILE_SIZES = ['B', 'KB', 'MB', 'GB', 'TB', 'PB'];

    /**
     * @param \Generated\Shared\Transfer\FileStorageDataTransfer $fileStorageDataTransfer
     *
     * @return \Generated\Shared\Transfer\FileStorageDataTransfer
     */
    public function expand(FileStorageDataTransfer $fileStorageDataTransfer): FileStorageDataTransfer
    {
        $fileStorageDataTransfer->requireSize();
        $fileDisplaySize = $this->getFileDisplaySize($fileStorageDataTransfer->getSize());

        return $fileStorageDataTransfer->setDisplaySize($fileDisplaySize);
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
