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
        'text/plain' => 'file',
        'image' => 'file-image',
        'jpg' => 'file-image',
        'jpeg' => 'file-image',
        'png' => 'file-image',
        'gif' => 'file-image',
        'tiff' => 'file-image',
        'audio' => 'file-audio',
        'video' => 'file-video',
        'application/pdf' => 'file-pdf',
        'pdf' => 'file-pdf',
        'application/msword' => 'file-word',
        'application/vnd.ms-word' => 'file-word',
        'application/vnd.oasis.opendocument.text' => 'file-word',
        'application/vnd.openxmlformats-officedocument.wordprocessingml' => 'file-word',
        'application/vnd.openxmlformats-officedocument.wordprocessingml.document' => 'file-word',
        'application/vnd.ms-excel' => 'file-excel',
        'application/vnd.openxmlformats-officedocument.spreadsheetml' => 'file-excel',
        'application/vnd.oasis.opendocument.spreadsheet' => 'file-excel',
        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' => 'file-excel',
        'application/vnd.ms-powerpoint' => 'file-powerpoint',
        'application/vnd.openxmlformats-officedocument.presentationml' => 'ffile-powerpoint',
        'application/vnd.oasis.opendocument.presentation' => 'file-powerpoint',
        'application/vnd.openxmlformats-officedocument.presentationml.presentation' => 'file-powerpoint',
        'application/gzip' => 'file-archive',
        'application/zip' => 'file-archive',
        'application/x-zip-compressed' => 'file-archive',
        'text/csv' => 'file-csv',
        'text/x-csv' => 'file-csv',
        'application/csv' => 'file-csv',
        'application/x-csv' => 'file-csv',
        'text/comma-separated-values' => 'file-csv',
        'text/x-comma-separated-values' => 'file-csv',
        'text/tab-separated-values' => 'file-csv',
        'csv' => 'file-csv',
    ];

    /**
     * @return string[]
     */
    public function getFileIconNames(): array
    {
        return static::ICON_NAME_MAP;
    }
}
