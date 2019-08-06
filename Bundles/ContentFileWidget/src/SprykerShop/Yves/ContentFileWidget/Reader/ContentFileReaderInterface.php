<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ContentFileWidget\Reader;

use Generated\Shared\Transfer\ContentFileListTypeTransfer;

interface ContentFileReaderInterface
{
    /**
     * @param \Generated\Shared\Transfer\ContentFileListTypeTransfer $contentFileListTypeTransfer
     * @param string $localeName
     *
     * @return array
     */
    public function getFileCollection(ContentFileListTypeTransfer $contentFileListTypeTransfer, string $localeName): array;
}
