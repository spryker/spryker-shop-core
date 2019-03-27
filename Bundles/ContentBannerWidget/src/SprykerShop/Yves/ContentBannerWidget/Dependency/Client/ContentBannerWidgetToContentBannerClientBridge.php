<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CategoryImageStorageWidget\Dependency\Client;

class ContentBannerWidgetToContentBannerClientBridge implements ContentBannerWidgetToContentBannerClientInterface
{
    /**
     * @var \Spryker\Client\ContentBanner\ContentBannerClient
     */
    protected $contentBannerClient;

    public function __construct($contentBannerClient)
    {
        $this->contentBannerClient = $contentBannerClient;
    }

    public function getExecutedBannerById(int $idContent, string $localeName): ?ExecutedBannerTransfer
    {
        return $this->contentBannerClient->getExecutedBannerById($idContent, $localeName);
    }
}