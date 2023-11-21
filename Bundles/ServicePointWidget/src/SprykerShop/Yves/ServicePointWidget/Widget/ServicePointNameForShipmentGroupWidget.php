<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ServicePointWidget\Widget;

use Generated\Shared\Transfer\ShipmentGroupTransfer;
use Spryker\Yves\Kernel\Widget\AbstractWidget;

class ServicePointNameForShipmentGroupWidget extends AbstractWidget
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
        return 'ServicePointNameForShipmentGroupWidget';
    }

    /**
     * @return string
     */
    public static function getTemplate(): string
    {
        return '@ServicePointWidget/views/service-point-name-for-shipment-group/service-point-name-for-shipment-group.twig';
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
            if ($itemTransfer->getServicePoint()) {
                $servicePointName = $itemTransfer->getServicePointOrFail()->getNameOrFail();

                break;
            }
        }

        $this->addParameter(static::PARAMETER_SERVICE_POINT_NAME, $servicePointName);
    }
}
