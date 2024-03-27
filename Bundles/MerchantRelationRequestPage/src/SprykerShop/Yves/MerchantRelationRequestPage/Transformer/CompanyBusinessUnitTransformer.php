<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\MerchantRelationRequestPage\Transformer;

use ArrayObject;
use Generated\Shared\Transfer\CompanyBusinessUnitTransfer;
use Symfony\Component\Form\CallbackTransformer;

class CompanyBusinessUnitTransformer implements CompanyBusinessUnitTransformerInterface
{
    /**
     * @return \Symfony\Component\Form\CallbackTransformer
     */
    public function transformCollection(): CallbackTransformer
    {
        return new CallbackTransformer(
            function () {
                return [];
            },
            function (array $assigneeCompanyBusinessUnitIds = []): ArrayObject {
                $assigneeCompanyBusinessUnits = new ArrayObject();
                foreach ($assigneeCompanyBusinessUnitIds as $idCompanyBusinessUnit) {
                    $assigneeCompanyBusinessUnits->append(
                        (new CompanyBusinessUnitTransfer())->setIdCompanyBusinessUnit($idCompanyBusinessUnit),
                    );
                }

                return $assigneeCompanyBusinessUnits;
            },
        );
    }
}
