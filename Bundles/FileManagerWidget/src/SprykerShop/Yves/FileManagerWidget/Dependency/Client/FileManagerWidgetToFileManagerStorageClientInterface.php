<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\FileManagerWidget\Dependency\Client;

use Generated\Shared\Transfer\FileStorageDataTransfer;

interface FileManagerWidgetToFileManagerStorageClientInterface
{
    /**
     * @param int $idFile
     * @param string $localeName
     *
     * @return null|\Generated\Shared\Transfer\FileStorageDataTransfer
     */
    public function findFileById(int $idFile, string $localeName): ?FileStorageDataTransfer;
}
