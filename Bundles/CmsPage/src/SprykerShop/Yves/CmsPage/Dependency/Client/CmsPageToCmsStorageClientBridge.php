<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CmsPage\Dependency\Client;

class CmsPageToCmsStorageClientBridge implements CmsPageToCmsStorageClientInterface
{
    /**
     * @var \Spryker\Client\CmsStorage\CmsStorageClientInterface
     */
    protected $cmsStorageClient;

    /**
     * @param \Spryker\Client\CmsStorage\CmsStorageClientInterface $cmsStorageClient
     */
    public function __construct($cmsStorageClient)
    {
        $this->cmsStorageClient = $cmsStorageClient;
    }

    /**
     * @param array $data
     *
     * @return \Generated\Shared\Transfer\LocaleCmsPageDataTransfer
     */
    public function mapCmsPageStorageData(array $data)
    {
        return $this->cmsStorageClient->mapCmsPageStorageData($data);
    }
}
