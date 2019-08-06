<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ShopCmsSlot\Business;

interface CmsSlotExecutorInterface
{
    /**
     * @param string $cmsSlotKey
     * @param array $provided
     * @param string[] $required
     * @param string[] $autofulfil
     *
     * @return string
     */
    public function getSlotContent(string $cmsSlotKey, array $provided, array $required, array $autofulfil): string;
}
