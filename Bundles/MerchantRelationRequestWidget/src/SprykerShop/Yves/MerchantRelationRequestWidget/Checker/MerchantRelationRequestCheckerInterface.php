<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\MerchantRelationRequestWidget\Checker;

interface MerchantRelationRequestCheckerInterface
{
    /**
     * @param string $merchantReference
     *
     * @return bool
     */
    public function isMerchantApplicableForRequest(string $merchantReference): bool;
}
