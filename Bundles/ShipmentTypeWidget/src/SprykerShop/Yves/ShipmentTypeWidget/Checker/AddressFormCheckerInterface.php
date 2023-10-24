<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ShipmentTypeWidget\Checker;

use Spryker\Shared\Kernel\Transfer\AbstractTransfer;
use Symfony\Component\Form\FormInterface;

interface AddressFormCheckerInterface
{
    /**
     * @param \Symfony\Component\Form\FormInterface $form
     *
     * @return bool
     */
    public function isDeliverToMultipleAddresses(FormInterface $form): bool;

    /**
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer|null $data
     *
     * @return bool
     */
    public function isApplicableForShipmentTypeAddressStepFormHydration(?AbstractTransfer $data): bool;
}
