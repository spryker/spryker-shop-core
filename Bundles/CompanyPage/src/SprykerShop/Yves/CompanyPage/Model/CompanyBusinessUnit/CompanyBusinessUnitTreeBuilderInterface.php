<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CompanyPage\Model\CompanyBusinessUnit;

interface CompanyBusinessUnitTreeBuilderInterface
{
    /**
     * @return array
     */
    public function getCompanyBusinessUnitTree(): array;
}
