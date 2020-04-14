<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CustomerReorderWidget\Plugin\CustomerPage;

use Generated\Shared\Transfer\OrderTransfer;
use Spryker\Yves\Kernel\Widget\AbstractWidget;
use Symfony\Component\Form\FormView;

/**
 * @method \SprykerShop\Yves\CustomerReorderWidget\CustomerReorderWidgetFactory getFactory()
 */
class CustomerReorderFormWidget extends AbstractWidget
{
    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     */
    public function __construct(OrderTransfer $orderTransfer)
    {
        $this->addParameter('order', $orderTransfer);
        $this->addParameter('form', $this->getCustomerReorderWidgetFormView());
    }

    /**
     * @return string
     */
    public static function getName(): string
    {
        return 'CustomerReorderFormWidget';
    }

    /**
     * @return string
     */
    public static function getTemplate(): string
    {
        return '@CustomerReorderWidget/views/customer-reorder-form/customer-reorder-form.twig';
    }

    /**
     * @return \Symfony\Component\Form\FormView
     */
    public function getCustomerReorderWidgetFormView(): FormView
    {
        return $this->getFactory()
            ->createCustomerReorderWidgetFormFactory()
            ->getCustomerReorderWidgetForm()
            ->createView();
    }
}
