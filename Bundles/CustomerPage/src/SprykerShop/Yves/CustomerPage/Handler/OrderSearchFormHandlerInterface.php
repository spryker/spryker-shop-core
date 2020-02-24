<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CustomerPage\Handler;

use ArrayObject;
use Symfony\Component\Form\FormInterface;

interface OrderSearchFormHandlerInterface
{
    /**
     * @param \Symfony\Component\Form\FormInterface $orderSearchForm
     *
     * @return \ArrayObject|\Generated\Shared\Transfer\FilterFieldTransfer[]
     */
    public function handleOrderSearchFormSubmit(FormInterface $orderSearchForm): ArrayObject;
}
