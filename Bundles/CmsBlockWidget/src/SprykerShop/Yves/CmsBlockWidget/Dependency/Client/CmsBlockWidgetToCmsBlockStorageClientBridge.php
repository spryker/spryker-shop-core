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
     * @deprecated Use \SprykerShop\Yves\CmsBlockWidget\Dependency\Client\CmsBlockWidgetToCmsBlockStorageClientBridge::findBlocksByKeys() instead.
     *
     * @param string[] $blockNames
     * @param string $localeName
     * @param string $storeName
     *
     * @return array
     */
    public function findBlocksByNames($blockNames, $localeName, $storeName)
    {
        return $this->cmsBlockStorageClient->findBlocksByNames($blockNames, $localeName, $storeName);
    }

    /**
     * @deprecated Use \SprykerShop\Yves\CmsBlockWidget\Dependency\Client\CmsBlockWidgetToCmsBlockStorageClientBridge::findBlockKeysByOptions() instead.
     *
     * @param array $options
     * @param string $localName
     *
     * @return array
     */
    public function findBlockNamesByOptions(array $options, $localName)
    {
        return $this->cmsBlockStorageClient->findBlockNamesByOptions($options, $localName);
    }

    /**
     * @deprecated Will be removed in the next major release.
     *
     * @param string $name
     *
     * @return string
     */
    public function generateBlockNameKey($name)
    {
        return $this->cmsBlockStorageClient->generateBlockNameKey($name);
    }

    /**
     * @param string[] $blockKeys
     * @param string $localeName
     * @param string $storeName
     *
     * @return array
     */
    public function findBlocksByKeys(array $blockKeys, string $localeName, string $storeName): array
    {
        return $this->cmsBlockStorageClient->findBlocksByKeys($blockKeys, $localeName, $storeName);
    }

    /**
     * @param array $options
     *
     * @return array
     */
    public function findBlockKeysByOptions(array $options): array
    {
        return $this->cmsBlockStorageClient->findBlockKeysByOptions($options);
    }

    /**
     * @return bool
     */
    public function isCmsBlockKeySupported(): bool
    {
        return method_exists($this->cmsBlockStorageClient, 'findBlocksByKeys');
    }
}
