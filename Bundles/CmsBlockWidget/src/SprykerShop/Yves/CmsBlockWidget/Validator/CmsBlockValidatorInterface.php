<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CmsBlockWidget\Validator;

use Generated\Shared\Transfer\CmsBlockTransfer;

interface CmsBlockValidatorInterface
{
    /**
     * @param \Generated\Shared\Transfer\CmsBlockTransfer $cmsBlockTransfer
     *
     * @return bool
     */
    public function isValid(CmsBlockTransfer $cmsBlockTransfer): bool;
}
