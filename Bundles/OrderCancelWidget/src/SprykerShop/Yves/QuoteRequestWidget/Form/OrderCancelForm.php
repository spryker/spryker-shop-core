<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\OrderCancelWidget\Form;

use Spryker\Yves\Kernel\Form\AbstractType;

/**
 * @method \SprykerShop\Yves\OrderCancelWidget\OrderCancelWidgetConfig getConfig()
 */
class OrderCancelForm extends AbstractType
{
    public const FORM_NAME = 'orderCancelForm';

    /**
     * @return string
     */
    public function getBlockPrefix()
    {
        return static::FORM_NAME;
    }
}
