<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ServicePointWidget\Validator;

use Spryker\Shared\Kernel\Transfer\AbstractTransfer;
use Symfony\Component\Form\FormInterface;

interface ServicePointFormValidatorInterface
{
    /**
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer $data
     * @param \Symfony\Component\Form\FormInterface $form
     *
     * @return void
     */
    public function validateSubmittedData(AbstractTransfer $data, FormInterface $form): void;
}
