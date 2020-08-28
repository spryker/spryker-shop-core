<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CustomerPage\CustomerAddress;

use Generated\Shared\Transfer\CustomerTransfer;

interface AddressChoicesResolverInterface
{
    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer|null $customerTransfer
     *
     * @return string[]
     */
    public function getAddressChoices(?CustomerTransfer $customerTransfer): array;

    /**
     * @param array $customerAddressChoices
     * @param bool $canDeliverToMultipleShippingAddresses
     *
     * @return string[]
     */
    public function getSingleShippingAddressChoices(array $customerAddressChoices, bool $canDeliverToMultipleShippingAddresses): array;
}
