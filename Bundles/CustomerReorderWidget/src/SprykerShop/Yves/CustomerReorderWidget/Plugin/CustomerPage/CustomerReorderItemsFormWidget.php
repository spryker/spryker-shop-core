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
class CustomerReorderItemsFormWidget extends AbstractWidget
{
    /**
     * @var \Symfony\Component\Form\FormView|null
     */
    protected static $customerReorderItemsWidgetFormView;

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param array $config
     */
    public function __construct(OrderTransfer $orderTransfer, array $config)
    {
        $this->addParameter('form', $this->getOrCreateCustomerReorderItemsWidgetFormView());
        $this->addParameter('order', $orderTransfer);
        $this->addParameter('config', $config);
    }

    /**
     * @return string
     */
    public static function getName(): string
    {
        return 'CustomerReorderItemsFormWidget';
    }

    /**
     * @return string
     */
    public static function getTemplate(): string
    {
        return '@CustomerReorderWidget/views/customer-reorder-items-form/customer-reorder-items-form.twig';
    }

    /**
     * @return \Symfony\Component\Form\FormView
     */
    public function createCustomerReorderItemsWidgetFormView(): FormView
    {
        return $this->getFactory()
            ->createCustomerReorderWidgetFormFactory()
            ->getCustomerReorderItemsWidgetForm()
            ->createView();
    }

    /**
     * @return \Symfony\Component\Form\FormView
     */
    protected function getOrCreateCustomerReorderItemsWidgetFormView(): FormView
    {
        if (static::$customerReorderItemsWidgetFormView === null) {
            static::$customerReorderItemsWidgetFormView = $this->createCustomerReorderItemsWidgetFormView();
        }

        return static::$customerReorderItemsWidgetFormView;
    }
}
