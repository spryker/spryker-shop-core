<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ShipmentTypeWidget\Hydrator;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Shared\Kernel\Transfer\AbstractTransfer;
use SprykerShop\Yves\ShipmentTypeWidget\Checker\AddressFormCheckerInterface;
use SprykerShop\Yves\ShipmentTypeWidget\Form\ShipmentTypeAddressStepForm;
use SprykerShop\Yves\ShipmentTypeWidget\Form\ShipmentTypeSubForm;
use Symfony\Component\Form\FormEvent;

class ShipmentTypeFormPreSetDataHydrator implements ShipmentTypeFormPreSetDataHydratorInterface
{
    /**
     * @uses \Spryker\Shared\ShipmentType\ShipmentTypeConfig::SHIPMENT_TYPE_DELIVERY
     *
     * @var string
     */
    protected const SHIPMENT_TYPE_DELIVERY = 'delivery';

    /**
     * @var \SprykerShop\Yves\ShipmentTypeWidget\Checker\AddressFormCheckerInterface
     */
    protected AddressFormCheckerInterface $addressFormChecker;

    /**
     * @param \SprykerShop\Yves\ShipmentTypeWidget\Checker\AddressFormCheckerInterface $addressFormChecker
     */
    public function __construct(AddressFormCheckerInterface $addressFormChecker)
    {
        $this->addressFormChecker = $addressFormChecker;
    }

    /**
     * @param \Symfony\Component\Form\FormEvent $event
     * @param array<string, mixed> $options
     *
     * @return void
     */
    public function hydrate(FormEvent $event, array $options): void
    {
        /** @var \Generated\Shared\Transfer\QuoteTransfer|\Generated\Shared\Transfer\ItemTransfer $data */
        $data = $event->getData();
        $form = $event->getForm();

        if (!$this->addressFormChecker->isApplicableForShipmentTypeAddressStepFormHydration($data)) {
            return;
        }

        $form->add(ShipmentTypeAddressStepForm::FIELD_SHIPMENT_TYPE, ShipmentTypeSubForm::class, [
            'required' => false,
            'mapped' => $data instanceof ItemTransfer,
            'label' => false,
            ShipmentTypeSubForm::OPTION_SHIPMENT_TYPES => $options[ShipmentTypeSubForm::OPTION_SHIPMENT_TYPES],
            ShipmentTypeSubForm::OPTION_AVAILABLE_SHIPMENT_TYPES => $options[ShipmentTypeSubForm::OPTION_AVAILABLE_SHIPMENT_TYPES],
            ShipmentTypeSubForm::OPTION_SELECTED_SHIPMENT_TYPE => $this->getSelectedShipmentTypeKey($data),
        ]);
    }

    /**
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer $data
     *
     * @return string|null
     */
    protected function getSelectedShipmentTypeKey(AbstractTransfer $data): ?string
    {
        /** @var \Generated\Shared\Transfer\QuoteTransfer|\Generated\Shared\Transfer\ItemTransfer $data */
        if ($data instanceof QuoteTransfer) {
            return $this->getSelectedShipmentTypeKeyForQuote($data);
        }

        return $this->getSelectedShipmentTypeKeyForItem($data);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return string|null
     */
    protected function getSelectedShipmentTypeKeyForQuote(QuoteTransfer $quoteTransfer): ?string
    {
        if ($this->isItemsHaveNoShipmentType($quoteTransfer)) {
            return static::SHIPMENT_TYPE_DELIVERY;
        }

        if ($this->isItemsHaveSameShipmentType($quoteTransfer)) {
            /** @var \Generated\Shared\Transfer\ItemTransfer $itemTransfer */
            $itemTransfer = $quoteTransfer->getItems()->getIterator()->current();

            return $itemTransfer->getShipmentTypeOrFail()->getKey();
        }

        return null;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return string|null
     */
    protected function getSelectedShipmentTypeKeyForItem(ItemTransfer $itemTransfer): ?string
    {
        return $itemTransfer->getShipmentType() ? $itemTransfer->getShipmentTypeOrFail()->getKey() : null;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    protected function isItemsHaveSameShipmentType(QuoteTransfer $quoteTransfer): bool
    {
        $shipmentTypeKeys = [];

        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            $shipmentTypeKey = $itemTransfer->getShipmentType() ? $itemTransfer->getShipmentTypeOrFail()->getKey() : null;

            if ($shipmentTypeKey) {
                $shipmentTypeKeys[$shipmentTypeKey][] = $itemTransfer;
            }
        }

        /** @var \ArrayObject<int, \Generated\Shared\Transfer\ItemTransfer> $items */
        $items = $quoteTransfer->getItems();

        return $items->count() && count($shipmentTypeKeys) && $items->count() === count(reset($shipmentTypeKeys));
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    protected function isItemsHaveNoShipmentType(QuoteTransfer $quoteTransfer): bool
    {
        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            $shipmentTypeKey = $itemTransfer->getShipmentType() ? $itemTransfer->getShipmentTypeOrFail()->getKey() : null;

            if ($shipmentTypeKey) {
                return false;
            }
        }

        return true;
    }
}
