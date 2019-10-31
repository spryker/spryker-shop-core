<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CmsSlotBlockWidget\Dependency\Client;

use Generated\Shared\Transfer\CmsSlotBlockStorageTransfer;

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
     * @return \Generated\Shared\Transfer\CmsSlotBlockStorageTransfer
     */
    public function getCmsSlotBlockCollection(
        string $cmsSlotTemplatePath,
        string $cmsSlotKey
    ): CmsSlotBlockStorageTransfer {
        //TODO: should be changed to client call
        $blocks = [
            [
                'blockName' => 'Category CMS page showcase for Top position',
            ],
            [
                'blockName' => 'Home Page',
            ],
            [
                'blockName' => 'Category CMS page showcase for Top position',
            ],
        ];

        return (new CmsSlotBlockStorageTransfer())->setCmsBlocks($blocks);
    }
}
