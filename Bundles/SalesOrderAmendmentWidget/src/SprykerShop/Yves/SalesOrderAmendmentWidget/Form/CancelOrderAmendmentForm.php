<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\SalesOrderAmendmentWidget\Form;

use Spryker\Yves\Kernel\Form\AbstractType;

class CancelOrderAmendmentForm extends AbstractType
{
    /**
     * @var string
     */
    public const FORM_NAME = 'cancelOrderAmendmentForm';

    /**
     * @return string
     */
    public function getBlockPrefix(): string
    {
        return static::FORM_NAME;
    }
}
