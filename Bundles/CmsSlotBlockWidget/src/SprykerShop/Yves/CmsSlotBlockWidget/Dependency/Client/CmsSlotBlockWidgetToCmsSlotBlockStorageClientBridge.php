<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CmsSlotBlockWidget\Dependency\Client;

use Generated\Shared\Transfer\CmsSlotBlockStorageDataTransfer;

class CmsSlotBlockWidgetToCmsSlotBlockStorageClientBridge implements CmsSlotBlockWidgetToCmsSlotBlockStorageClientInterface
{
    protected $cmsSlotBlockStorageClient;
    
    public function __construct()
    {
    }

    /**
     * @param string $cmsSlotTemplatePath
     * @param string $cmsSlotKey
     *
     * @return \Generated\Shared\Transfer\CmsSlotBlockStorageDataTransfer
     */
    public function getCmsSlotBlockCollection(
        string $cmsSlotTemplatePath,
        string $cmsSlotKey
    ): CmsSlotBlockStorageDataTransfer {
        //TODO: should be changed to client call
        $blocks = [
            [
                'blockKey' => 'blck-2',
                'conditions' => ['product' => ['all' => false, 'productIds' => [3], 'categoryIds' => []]],
            ],
            [
                'blockKey' => 'blck-4',
                'conditions' => ['product' => ['all' => false, 'productIds' => [2], 'categoryIds' => [4]]],
            ],
        ];

        return (new CmsSlotBlockStorageDataTransfer())->setCmsBlocks($blocks);
    }
}
