<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\SalesOrderAmendmentWidget\Widget;

use Generated\Shared\Transfer\OrderTransfer;
use Spryker\Yves\Kernel\Widget\AbstractWidget;

/**
 * @method \SprykerShop\Yves\SalesOrderAmendmentWidget\SalesOrderAmendmentWidgetFactory getFactory()
 * @method \SprykerShop\Yves\SalesOrderAmendmentWidget\SalesOrderAmendmentWidgetConfig getConfig()
 */
class OrderAmendmentWidget extends AbstractWidget
{
    /**
     * @var string
     */
    protected const PARAMETER_ORDER = 'order';

    /**
     * @var string
     */
    protected const PARAMETER_FORM = 'form';

    /**
     * @var string
     */
    protected const PARAMETER_IS_ORDER_AMENDMENT_CONFIRMATION_ENABLED = 'isOrderAmendmentConfirmationEnabled';

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     */
    public function __construct(OrderTransfer $orderTransfer)
    {
        $this->addFormParameter();
        $this->addOrderParameter($orderTransfer);
        $this->addIsOrderAmendmentConfirmationEnabledParameter();
    }

    /**
     * @return string
     */
    public static function getName(): string
    {
        return 'OrderAmendmentWidget';
    }

    /**
     * @return string
     */
    public static function getTemplate(): string
    {
        return '@SalesOrderAmendmentWidget/views/order-amendment/order-amendment.twig';
    }

    /**
     * @return void
     */
    protected function addFormParameter(): void
    {
        $this->addParameter(static::PARAMETER_FORM, $this->getFactory()->getOrderAmendmentForm()->createView());
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
     * @return void
     */
    protected function addIsOrderAmendmentConfirmationEnabledParameter(): void
    {
        $this->addParameter(
            static::PARAMETER_IS_ORDER_AMENDMENT_CONFIRMATION_ENABLED,
            $this->getConfig()->isOrderAmendmentConfirmationEnabled(),
        );
    }
}
