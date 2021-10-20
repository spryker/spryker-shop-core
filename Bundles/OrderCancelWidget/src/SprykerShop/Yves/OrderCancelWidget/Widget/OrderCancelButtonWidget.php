<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\OrderCancelWidget\Widget;

use Generated\Shared\Transfer\OrderTransfer;
use Spryker\Yves\Kernel\Widget\AbstractWidget;

/**
 * @method \SprykerShop\Yves\OrderCancelWidget\OrderCancelWidgetFactory getFactory()
 */
class OrderCancelButtonWidget extends AbstractWidget
{
    /**
     * @var string
     */
    protected const PARAMETER_IS_VISIBLE = 'isVisible';

    /**
     * @var string
     */
    protected const PARAMETER_FORM = 'form';

    /**
     * @var string
     */
    protected const PARAMETER_ORDER = 'order';

    /**
     * @var string
     */
    protected const PARAMETER_RETURN_URL = 'returnUrl';

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param string $returnUrl
     */
    public function __construct(OrderTransfer $orderTransfer, string $returnUrl)
    {
        $this->addIsVisibleParameter($orderTransfer);
        $this->addOrderParameter($orderTransfer);
        $this->addReturnUrlParameter($returnUrl);
        $this->addFormParameter();
    }

    /**
     * @return string
     */
    public static function getName(): string
    {
        return 'OrderCancelButtonWidget';
    }

    /**
     * @return string
     */
    public static function getTemplate(): string
    {
        return '@OrderCancelWidget/views/order-cancel-button/order-cancel-button.twig';
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return void
     */
    protected function addIsVisibleParameter(OrderTransfer $orderTransfer): void
    {
        $this->addParameter(
            static::PARAMETER_IS_VISIBLE,
            $orderTransfer->getIsCancellable() && $this->isCustomerApplicableForCancel($orderTransfer)
        );
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return void
     */
    protected function addOrderParameter(OrderTransfer $orderTransfer): void
    {
        $this->addParameter(static::PARAMETER_ORDER, $orderTransfer);
    }

    /**
     * @param string $returnUrl
     *
     * @return void
     */
    protected function addReturnUrlParameter(string $returnUrl): void
    {
        $this->addParameter(static::PARAMETER_RETURN_URL, $returnUrl);
    }

    /**
     * @return void
     */
    protected function addFormParameter(): void
    {
        $this->addParameter(static::PARAMETER_FORM, $this->getFactory()->getOrderCancelForm()->createView());
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return bool
     */
    protected function isCustomerApplicableForCancel(OrderTransfer $orderTransfer): bool
    {
        $customerTransfer = $this->getFactory()
            ->getCustomerClient()
            ->getCustomer();

        if (!$customerTransfer) {
            return false;
        }

        return $customerTransfer->getCustomerReference() === $orderTransfer->getCustomerReference();
    }
}
