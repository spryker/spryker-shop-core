<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ContentFileWidget\Expander;

use Generated\Shared\Transfer\FileStorageDataTransfer;
use SprykerShop\Yves\ContentFileWidget\ContentFileWidgetConfig;

class FileStorageDataExpander implements FileStorageDataExpanderInterface
{
    protected const KEY_DEFAULT_ICON_NAME = 'text/plain';

    /**
     * @var \SprykerShop\Yves\ContentFileWidget\ContentFileWidgetConfig
     */
    protected $contentFileWidgetConfig;

    /**
     * @param \SprykerShop\Yves\ContentFileWidget\ContentFileWidgetConfig $contentFileWidgetConfig
     */
    public function __construct(ContentFileWidgetConfig $contentFileWidgetConfig) {
        $this->contentFileWidgetConfig = $contentFileWidgetConfig;
    }

    /**
     * @param \Generated\Shared\Transfer\FileStorageDataTransfer $fileStorageDataTransfer
     *
     * @return string
     */
    public function getIconName(FileStorageDataTransfer $fileStorageDataTransfer): string
    {
        $iconNames = $this->contentFileWidgetConfig->getFileIconNames();
        $fileType = explode('/', $fileStorageDataTransfer->getType())[0];

        return $iconNames[$fileStorageDataTransfer->getType()]
            ?? $iconNames[$fileType]
            ?? $this->getFileIconNameByExtension($fileStorageDataTransfer->getFileName());
    }

    /**
     * @param string $fileName
     *
     * @return string
     */
    protected function getFileIconNameByExtension(string $fileName): string
    {
        $iconNames = $this->contentFileWidgetConfig->getFileIconNames();
        $fileExtension = pathinfo($fileName, PATHINFO_EXTENSION);

        return $iconNames[$fileExtension] ?? $iconNames[static::KEY_DEFAULT_ICON_NAME];
    }
}
