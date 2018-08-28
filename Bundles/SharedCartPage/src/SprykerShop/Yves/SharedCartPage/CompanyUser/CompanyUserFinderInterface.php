<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\SharedCartPage\CompanyUser;

use ArrayObject;

interface CompanyUserFinderInterface
{
    /**
     * @param int $idQuote
     *
     * @return \ArrayObject
     */
    public function getCompanyUsersShareDetails(int $idQuote): ArrayObject;

    /**
     * @return string[]
     */
    public function getCompanyUserNames(): array;
}
