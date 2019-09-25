<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ShopCmsSlot\Dependency\Client;

interface ShopCmsSlotToCmsSlotClientInterface
{
    /**
     * @param string[] $dataKeys
     *
     * @return array
     */
    public function getCmsSlotExternalDataByKeys(array $dataKeys): array;
}
