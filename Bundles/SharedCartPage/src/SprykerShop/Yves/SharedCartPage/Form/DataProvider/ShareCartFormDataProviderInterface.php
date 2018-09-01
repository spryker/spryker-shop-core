<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\SharedCartPage\Form\DataProvider;

use ArrayObject;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\ShareCartRequestTransfer;

interface ShareCartFormDataProviderInterface
{
    /**
     * @param int $idQuote
     * @param \ArrayObject|\Generated\Shared\Transfer\ShareDetailTransfer[]|null $quoteShareDetails
     *
     * @return \Generated\Shared\Transfer\ShareCartRequestTransfer
     */
    public function getData($idQuote, ?ArrayObject $quoteShareDetails = null): ShareCartRequestTransfer;

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer|null $customerTransfer
     *
     * @return array
     */
    public function getOptions(?CustomerTransfer $customerTransfer = null): array;

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer|null $customerTransfer
     *
     * @return array
     */
    public function getCompanyUserNames(?CustomerTransfer $customerTransfer = null): array;
}
