<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\QuickOrderPage\File\Parser\UploadedFile;

interface UploadedFileTypeSanitizerInterface
{
    /**
     * @param mixed $value
     *
     * @return mixed
     */
    public function sanitizeQuantity($value);
}
