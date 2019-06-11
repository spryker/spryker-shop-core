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
     * @param string $contentKey
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\ContentFileListTypeTransfer|null
     */
    public function executeFileListTypeByKey(string $contentKey, string $localeName): ?ContentFileListTypeTransfer
    {
        return $this->contentFileClient->executeFileListTypeByKey($contentKey, $localeName);
    }
}
