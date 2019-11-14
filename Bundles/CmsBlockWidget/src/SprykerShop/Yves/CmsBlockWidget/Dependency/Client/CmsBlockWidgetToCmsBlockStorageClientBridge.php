<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CmsBlockWidget\Dependency\Client;

class CmsBlockWidgetToCmsBlockStorageClientBridge implements CmsBlockWidgetToCmsBlockStorageClientInterface
{
    /**
     * @var \Spryker\Client\CmsBlockStorage\CmsBlockStorageClientInterface
     */
    protected $cmsBlockStorageClient;

    /**
     * @param \Spryker\Client\CmsBlockStorage\CmsBlockStorageClientInterface $cmsBlockStorageClient
     */
    public function __construct($cmsBlockStorageClient)
    {
        $this->cmsBlockStorageClient = $cmsBlockStorageClient;
    }

    /**
     * @param array $options
     * @param string $localeName
     * @param string $storeName
     *
     * @return array
     */
    public function getCmsBlocksByOptions(array $options, string $localeName, string $storeName): array
    {
        return $this->cmsBlockStorageClient->getCmsBlocksByOptions($options, $localeName, $storeName);
    }
}
