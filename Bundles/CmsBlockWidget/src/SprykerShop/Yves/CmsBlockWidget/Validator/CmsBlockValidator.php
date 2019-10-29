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
     * @param \Generated\Shared\Transfer\SpyCmsBlockEntityTransfer $cmsBlockEntityTransfer
     *
     * @return bool
     */
    public function isValid(SpyCmsBlockEntityTransfer $cmsBlockEntityTransfer): bool
    {
        return $cmsBlockEntityTransfer->getIsActive() &&
            $cmsBlockEntityTransfer->getCmsBlockTemplate() &&
            $this->isDateValid($cmsBlockEntityTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\SpyCmsBlockEntityTransfer $cmsBlockEntityTransfer
     *
     * @return bool
     */
    protected function isDateValid(SpyCmsBlockEntityTransfer $cmsBlockEntityTransfer): bool
    {
        $dateToCompare = new DateTime();

        if ($cmsBlockEntityTransfer->getValidFrom() !== null) {
            $validFrom = new DateTime($cmsBlockEntityTransfer->getValidFrom());

            if ($dateToCompare <= $validFrom) {
                return false;
            }
        }

        if ($cmsBlockEntityTransfer->getValidTo() !== null) {
            $validTo = new DateTime($cmsBlockEntityTransfer->getValidTo());

            if ($dateToCompare > $validTo) {
                return false;
            }
        }

        return true;
    }
}
