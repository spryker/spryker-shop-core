<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\SalesServicePointWidget\Widget;

use Generated\Shared\Transfer\ShipmentGroupTransfer;
use Spryker\Yves\Kernel\Widget\AbstractWidget;

/**
 * @method \SprykerShop\Yves\SalesServicePointWidget\SalesServicePointWidgetFactory getFactory()
 * @method \SprykerShop\Yves\SalesServicePointWidget\SalesServicePointWidgetConfig getConfig()
 */
class SalesServicePointNameForShipmentGroupWidget extends AbstractWidget
{
    /**
     * @var string
     */
    protected const PARAMETER_SERVICE_POINT_NAME = 'servicePointName';

    /**
     * @param \Generated\Shared\Transfer\ShipmentGroupTransfer $shipmentGroupTransfer
     */
    public function __construct(ShipmentGroupTransfer $shipmentGroupTransfer)
    {
        $this->addServicePointNameParameter($shipmentGroupTransfer);
    }

    /**
     * @return string
     */
    public static function getName(): string
    {
        return 'SalesServicePointNameForShipmentGroupWidget';
    }

    /**
     * @return string
     */
    public static function getTemplate(): string
    {
        return '@SalesServicePointWidget/views/service-point-name-for-shipment-group/service-point-name-for-shipment-group.twig';
    }

    /**
     * @param \Generated\Shared\Transfer\ShipmentGroupTransfer $shipmentGroupTransfer
     *
     * @return void
     */
    protected function addServicePointNameParameter(ShipmentGroupTransfer $shipmentGroupTransfer): void
    {
        $servicePointName = '';

        foreach ($shipmentGroupTransfer->getItems() as $itemTransfer) {
            if ($itemTransfer->getSalesOrderItemServicePoint()) {
                $servicePointName = $itemTransfer->getSalesOrderItemServicePointOrFail()->getNameOrFail();

                break;
            }
        }

        $this->addParameter(static::PARAMETER_SERVICE_POINT_NAME, $servicePointName);
    }
}
