<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ContentFileWidget\Dependency\Client;

interface ContentFileWidgetToFileManagerStorageClientInterface
{
    /**
     * @param int $idFile
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\FileStorageDataTransfer|null
     */
    public function findFileById(int $idFile, string $localeName);
}
