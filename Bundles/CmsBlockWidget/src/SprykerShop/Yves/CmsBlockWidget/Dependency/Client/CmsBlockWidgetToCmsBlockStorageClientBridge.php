<?php
/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CmsBlockWidget\Dependency\Client;

use Spryker\Client\CmsBlockStorage\CmsBlockStorageClientInterface;

class CmsBlockWidgetToCmsBlockStorageClientBridge implements CmsBlockWidgetToCmsBlockStorageClientInterface
{

    /**
     * @var CmsBlockStorageClientInterface
     */
    protected $cmsBlockStorageClient;

    /**
     * @param CmsBlockStorageClientInterface $cmsBlockStorageClient
     */
    public function __construct($cmsBlockStorageClient)
    {
        $this->cmsBlockStorageClient = $cmsBlockStorageClient;
    }

    /**
     * @param string[] $blockNames
     * @param string $localeName
     *
     * @return array
     */
    public function findBlocksByNames($blockNames, $localeName)
    {
        return $this->cmsBlockStorageClient->findBlocksByNames($blockNames, $localeName);
    }

    /**
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
     * @param string $name
     *
     * @return string
     */
    public function generateBlockNameKey($name)
    {
        return $this->cmsBlockStorageClient->generateBlockNameKey($name);
    }

}
