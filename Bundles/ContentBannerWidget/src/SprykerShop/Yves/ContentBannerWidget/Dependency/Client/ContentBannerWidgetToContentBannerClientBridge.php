<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ContentBannerWidget\Dependency\Client;

use Generated\Shared\Transfer\BannerTypeTransfer;

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

    /**
     * @param int $idContent
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\BannerTypeTransfer|null
     */
    public function findBannerById(int $idContent, string $localeName): ?BannerTypeTransfer
    {
        return $this->contentBannerClient->findBannerById($idContent, $localeName);
    }
}