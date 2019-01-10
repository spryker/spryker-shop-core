<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\QuickOrderPage\UploadOrder;

use Symfony\Component\HttpFoundation\File\UploadedFile;

interface UploadOrderParserInterface
{
    /**
     * @param \Symfony\Component\HttpFoundation\File\UploadedFile $uploadOrder
     *
     * @return \Generated\Shared\Transfer\QuickOrderItemTransfer[]
     */
    public function parse(UploadedFile $uploadOrder): array;
}
