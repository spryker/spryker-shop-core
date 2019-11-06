<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\CmsBlockWidget\src\SprykerShop\Yves\CmsBlockWidget\Validator;

use Generated\Shared\Transfer\SpyCmsBlockEntityTransfer;

interface CmsBlockValidatorInterface
{
    /**
     * @param \Generated\Shared\Transfer\SpyCmsBlockEntityTransfer $cmsBlockEntityTransfer
     *
     * @return bool
     */
    public function isValid(SpyCmsBlockEntityTransfer $cmsBlockEntityTransfer): bool;
}
