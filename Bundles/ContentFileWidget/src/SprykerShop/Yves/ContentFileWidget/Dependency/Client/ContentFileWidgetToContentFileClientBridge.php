<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ContentFileWidget\Dependency\Client;

use Generated\Shared\Transfer\ContentFileListTypeTransfer;

class ContentFileWidgetToContentFileClientBridge implements ContentFileWidgetToContentFileClientInterface
{

    /**
     * @param int $idContent
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\ContentFileListTypeTransfer|null
     */
    public function executeContentFileListTypeById(int $idContent, string $localeName): ?ContentFileListTypeTransfer
    {
        // TODO: have to be changed to real data from client
        return new ContentFileListTypeTransfer();
    }
}