<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShopTest\Yves\CmsSlotBlockWidget;

use Codeception\Actor;
use Generated\Shared\DataBuilder\CmsSlotContentRequestBuilder;
use Generated\Shared\Transfer\CmsSlotContentRequestTransfer;
use Generated\Shared\Transfer\CmsSlotParamsTransfer;

/**
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method void pause()
 *
 * @SuppressWarnings(PHPMD)
 */
class CmsSlotBlockWidgetYvesTester extends Actor
{
    use _generated\CmsSlotBlockWidgetYvesTesterActions;

    /**
     * @param array $seedData
     *
     * @return \Generated\Shared\Transfer\CmsSlotContentRequestTransfer
     */
    public function getCmsSlotContentRequestTransfer(array $seedData = []): CmsSlotContentRequestTransfer
    {
        $defaultData = [
            CmsSlotContentRequestTransfer::CMS_SLOT_PARAMS => new CmsSlotParamsTransfer(),
        ];

        return (new CmsSlotContentRequestBuilder($seedData + $defaultData))->build();
    }
}
