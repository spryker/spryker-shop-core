<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\FileManagerWidget\Dependency\Client;

use Generated\Shared\Transfer\ReadFileTransfer;

interface FileManagerWidgetToFileManagerClientInterface
{
    /**
     * @param \Generated\Shared\Transfer\ReadFileTransfer $readFileTransfer
     *
     * @return \Generated\Shared\Transfer\FileManagerDataTransfer
     */
    public function readFile(ReadFileTransfer $readFileTransfer);
}
