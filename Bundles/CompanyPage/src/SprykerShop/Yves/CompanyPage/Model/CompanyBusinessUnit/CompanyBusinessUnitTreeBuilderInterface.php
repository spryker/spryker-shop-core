<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CompanyPage\Model\CompanyBusinessUnit;

use Generated\Shared\Transfer\CompanyBusinessUnitCollectionTransfer;

interface CompanyBusinessUnitTreeBuilderInterface
{
    /**
     * @return \Generated\Shared\Transfer\CompanyBusinessUnitCollectionTransfer
     */
    public function getCompanyBusinessUnitTree(): CompanyBusinessUnitCollectionTransfer;
}
