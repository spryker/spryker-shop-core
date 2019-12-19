<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CmsBlockWidget\Validator;

use DateTime;
use Generated\Shared\Transfer\CmsBlockTransfer;

class CmsBlockValidator implements CmsBlockValidatorInterface
{
    /**
     * @param \Generated\Shared\Transfer\CmsBlockTransfer $cmsBlockTransfer
     *
     * @return bool
     */
    public function isValid(CmsBlockTransfer $cmsBlockTransfer): bool
    {
        return $cmsBlockTransfer->getIsActive() &&
            $cmsBlockTransfer->getCmsBlockTemplate() &&
            $this->isDateValid($cmsBlockTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\CmsBlockTransfer $cmsBlockTransfer
     *
     * @return bool
     */
    protected function isDateValid(CmsBlockTransfer $cmsBlockTransfer): bool
    {
        $dateToCompare = new DateTime();

        if ($cmsBlockTransfer->getValidFrom() !== null) {
            $validFrom = new DateTime($cmsBlockTransfer->getValidFrom());

            if ($dateToCompare <= $validFrom) {
                return false;
            }
        }

        if ($cmsBlockTransfer->getValidTo() !== null) {
            $validTo = new DateTime($cmsBlockTransfer->getValidTo());

            if ($dateToCompare > $validTo) {
                return false;
            }
        }

        return true;
    }
}
