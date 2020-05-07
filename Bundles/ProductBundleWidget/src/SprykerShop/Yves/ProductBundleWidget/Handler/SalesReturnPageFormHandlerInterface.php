<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductBundleWidget\Handler;

use Generated\Shared\Transfer\ReturnCreateRequestTransfer;

interface SalesReturnPageFormHandlerInterface
{
    /**
     * @param array $returnItemsList
     * @param \Generated\Shared\Transfer\ReturnCreateRequestTransfer $returnCreateRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ReturnCreateRequestTransfer
     */
    public function handleFormData(array $returnItemsList, ReturnCreateRequestTransfer $returnCreateRequestTransfer): ReturnCreateRequestTransfer;
}
