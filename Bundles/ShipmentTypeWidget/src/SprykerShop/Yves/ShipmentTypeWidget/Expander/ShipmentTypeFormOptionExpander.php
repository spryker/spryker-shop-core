<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ShipmentTypeWidget\Expander;

use Generated\Shared\Transfer\QuoteTransfer;
use SprykerShop\Yves\ShipmentTypeWidget\Form\ShipmentTypeSubForm;
use SprykerShop\Yves\ShipmentTypeWidget\Reader\ShipmentTypeReaderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ShipmentTypeFormOptionExpander implements ShipmentTypeFormOptionExpanderInterface
{
    /**
     * @uses \SprykerShop\Yves\CustomerPage\Form\CheckoutMultiShippingAddressesForm::OPTION_MULTI_SHIPPING_OPTIONS
     *
     * @var string
     */
    protected const OPTION_MULTI_SHIPPING_OPTIONS = 'multiShippingOptions';

    /**
     * @var \SprykerShop\Yves\ShipmentTypeWidget\Reader\ShipmentTypeReaderInterface
     */
    protected ShipmentTypeReaderInterface $shipmentTypeReader;

    /**
     * @param \SprykerShop\Yves\ShipmentTypeWidget\Reader\ShipmentTypeReaderInterface $shipmentTypeReader
     */
    public function __construct(ShipmentTypeReaderInterface $shipmentTypeReader)
    {
        $this->shipmentTypeReader = $shipmentTypeReader;
    }

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver
            ->setDefined(ShipmentTypeSubForm::OPTION_SHIPMENT_TYPES)
            ->setDefined(ShipmentTypeSubForm::OPTION_AVAILABLE_SHIPMENT_TYPES);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param array<string, mixed> $options
     *
     * @return array<string, mixed>
     */
    public function expandOptions(QuoteTransfer $quoteTransfer, array $options): array
    {
        $options[ShipmentTypeSubForm::OPTION_SHIPMENT_TYPES] = $this->shipmentTypeReader->getShipmentTypes($quoteTransfer);
        $options[ShipmentTypeSubForm::OPTION_AVAILABLE_SHIPMENT_TYPES] = $this->shipmentTypeReader->getAvailableShipmentTypes($quoteTransfer);

        return $this->duplicateShipmentTypeOptionsForItemLevel($options);
    }

    /**
     * @param array<string, mixed> $options
     *
     * @return array<string, mixed>
     */
    protected function duplicateShipmentTypeOptionsForItemLevel(array $options): array
    {
        if (!isset($options[static::OPTION_MULTI_SHIPPING_OPTIONS])) {
            return $options;
        }

        $options[static::OPTION_MULTI_SHIPPING_OPTIONS][ShipmentTypeSubForm::OPTION_SHIPMENT_TYPES] = $options[ShipmentTypeSubForm::OPTION_SHIPMENT_TYPES];
        $options[static::OPTION_MULTI_SHIPPING_OPTIONS][ShipmentTypeSubForm::OPTION_AVAILABLE_SHIPMENT_TYPES] = $options[ShipmentTypeSubForm::OPTION_AVAILABLE_SHIPMENT_TYPES];

        return $options;
    }
}
