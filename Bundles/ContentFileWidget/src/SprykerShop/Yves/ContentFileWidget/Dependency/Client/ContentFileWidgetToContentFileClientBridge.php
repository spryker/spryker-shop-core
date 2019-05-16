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
     * @var \Spryker\Client\ContentFile\ContentFileClientInterface
     */
    protected $contentFileClient;

    /**
     * @param \Spryker\Client\ContentFile\ContentFileClientInterface $contentFileClient
     */
    public function __construct($contentFileClient)
    {
        $this->contentFileClient = $contentFileClient;
    }

    /**
     * @param int $idContent
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\ContentFileListTypeTransfer|null
     */
    public function executeContentFileListTypeById(int $idContent, string $localeName): ?ContentFileListTypeTransfer
    {
        // TODO: Have to be deleted
        return (new ContentFileListTypeTransfer())->setFileIds([1,2,3,4]);

        return $this->contentFileClient->executeContentFileListTypeById($idContent, $localeName);
    }
}
