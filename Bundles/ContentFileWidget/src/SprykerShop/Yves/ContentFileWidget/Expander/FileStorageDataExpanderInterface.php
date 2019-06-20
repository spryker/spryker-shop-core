<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ContentFileWidget\Expander;

use Generated\Shared\Transfer\FileStorageDataTransfer;

interface FileStorageDataExpanderInterface
{
    /**
     * @param \Generated\Shared\Transfer\FileStorageDataTransfer $fileStorageDataTransfer
     *
     * @return \Generated\Shared\Transfer\FileStorageDataTransfer
     */
    public function expand(FileStorageDataTransfer $fileStorageDataTransfer): FileStorageDataTransfer;
}
