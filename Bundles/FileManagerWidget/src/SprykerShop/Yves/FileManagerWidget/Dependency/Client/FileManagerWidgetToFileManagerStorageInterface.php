<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\FileManagerWidget\Dependency\Client;

use Generated\Shared\Transfer\FileManagerStorageTransfer;

interface FileManagerWidgetToFileManagerStorageInterface
{
    /**
     * @param int $fileId
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\FileManagerStorageTransfer|null
     */
    public function findFileById(int $fileId, string $localeName): ?FileManagerStorageTransfer;
}
