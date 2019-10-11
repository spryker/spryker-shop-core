<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\CmsBlockWidget\src\SprykerShop\Yves\CmsBlockWidget\Validator;

use DateTime;
use Generated\Shared\Transfer\SpyCmsBlockEntityTransfer;

class CmsBlockValidator implements CmsBlockValidatorInterface
{
    /**
     * @param \Generated\Shared\Transfer\SpyCmsBlockEntityTransfer $spyCmsBlockTransfer
     *
     * @return bool
     */
    public function isValid(SpyCmsBlockEntityTransfer $spyCmsBlockTransfer): bool
    {
        if (!$spyCmsBlockTransfer->getCmsBlockTemplate()) {
            return false;
        }

        $dateToCompare = new DateTime();

        if ($spyCmsBlockTransfer->getValidFrom() !== null) {
            $validFrom = new DateTime($spyCmsBlockTransfer->getValidFrom());

            if ($dateToCompare <= $validFrom) {
                return false;
            }
        }

        if ($spyCmsBlockTransfer->getValidTo() !== null) {
            $validTo = new DateTime($spyCmsBlockTransfer->getValidTo());

            if ($dateToCompare > $validTo) {
                return false;
            }
        }

        return true;
    }
}
