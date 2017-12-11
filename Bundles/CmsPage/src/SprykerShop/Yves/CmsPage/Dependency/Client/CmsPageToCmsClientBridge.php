<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CmsPage\Dependency\Client;

use Generated\Shared\Transfer\FlattenedLocaleCmsPageDataRequestTransfer;

class CmsPageToCmsClientBridge implements CmsPageToCmsClientInterface
{
    /**
     * @var \Spryker\Client\Cms\CmsClientInterface
     */
    protected $cmsClient;

    /**
     * @param \Spryker\Client\Cms\CmsClientInterface $cmsClient
     */
    public function __construct($cmsClient)
    {
        $this->cmsClient = $cmsClient;
    }

    /**
     * @param \Generated\Shared\Transfer\FlattenedLocaleCmsPageDataRequestTransfer $flattenedLocaleCmsPageDataRequestTransfer
     *
     * @return \Generated\Shared\Transfer\FlattenedLocaleCmsPageDataRequestTransfer
     */
    public function getFlattenedLocaleCmsPageData(FlattenedLocaleCmsPageDataRequestTransfer $flattenedLocaleCmsPageDataRequestTransfer)
    {
        return $this->cmsClient->getFlattenedLocaleCmsPageData($flattenedLocaleCmsPageDataRequestTransfer);
    }
}
