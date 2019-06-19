<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ContentFileWidget;

use Spryker\Yves\Kernel\AbstractBundleConfig;

class ContentFileWidgetConfig extends AbstractBundleConfig
{
    protected const ICON_NAME_MAP = [
        'application/csv' => 'file-csv',
        'application/gzip' => 'file-archive',
        'application/msword' => 'file-word',
        'application/pdf' => 'file-pdf',
        'application/vnd.ms-word' => 'file-word',
        'application/x-csv' => 'file-csv',
        'application/x-zip-compressed' => 'file-archive',
        'application/zip' => 'file-archive',
        'audio' => 'file-audio',
        'csv' => 'file-csv',
        'gif' => 'file-image',
        'image' => 'file-image',
        'jpeg' => 'file-image',
        'jpg' => 'file-image',
        'pdf' => 'file-pdf',
        'png' => 'file-image',
        'text/csv' => 'file-csv',
        'text/comma-separated-values' => 'file-csv',
        'text/plain' => 'file-text',
        'text/tab-separated-values' => 'file-csv',
        'text/x-comma-separated-values' => 'file-csv',
        'text/x-csv' => 'file-csv',
        'tiff' => 'file-image',
        'video' => 'file-video',
    ];

    /**
     * @return string[]
     */
    public function getFileIconNames(): array
    {
        return static::ICON_NAME_MAP;
    }
}
