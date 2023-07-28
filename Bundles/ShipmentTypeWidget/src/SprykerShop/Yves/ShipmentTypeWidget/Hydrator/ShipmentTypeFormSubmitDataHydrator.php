<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ShipmentTypeWidget\Hydrator;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\ShipmentTypeTransfer;
use Spryker\Shared\Kernel\Transfer\AbstractTransfer;
use SprykerShop\Yves\ShipmentTypeWidget\Checker\AddressFormCheckerInterface;
use SprykerShop\Yves\ShipmentTypeWidget\Form\ShipmentTypeAddressStepForm;
use SprykerShop\Yves\ShipmentTypeWidget\Form\ShipmentTypeSubForm;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormInterface;

class ShipmentTypeFormSubmitDataHydrator implements ShipmentTypeFormSubmitDataHydratorInterface
{
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
        /** @var \Generated\Shared\Transfer\QuoteTransfer|\Generated\Shared\Transfer\ItemTransfer|null $data */
        $data = $event->getData();
        $form = $event->getForm();

        if (!$this->isApplicable($data)) {
            return;
        }

        if ($data instanceof QuoteTransfer) {
            $this->hydrateShipmentTypesToQuote($data, $form, $event, $options);

            return;
        }

        /** @var \Generated\Shared\Transfer\ItemTransfer $data */
        $this->hydrateShipmentTypeToItem($data, $form, $event);
    }

    /**
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer|null $data
     *
     * @return bool
     */
    protected function isApplicable(?AbstractTransfer $data): bool
    {
        return $data instanceof QuoteTransfer || $data instanceof ItemTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Symfony\Component\Form\FormInterface $form
     * @param \Symfony\Component\Form\FormEvent $event
     * @param array<string, mixed> $options
     *
     * @return void
     */
    protected function hydrateShipmentTypesToQuote(QuoteTransfer $quoteTransfer, FormInterface $form, FormEvent $event, array $options): void
    {
        $availableShipmentTypes = $options[ShipmentTypeSubForm::OPTION_AVAILABLE_SHIPMENT_TYPES];

        if ($this->addressFormChecker->isDeliverToMultipleAddresses($form)) {
            $quoteTransfer = $this->setShipmentTypesToItemLevel($quoteTransfer, $availableShipmentTypes);
            $event->setData($quoteTransfer);

            return;
        }

        $shipmentTypeKey = $form->get(ShipmentTypeAddressStepForm::FIELD_SHIPMENT_TYPE)
            ->get(ShipmentTypeSubForm::FIELD_SHIPMENT_TYPE_KEY)
            ->getData();

        $quoteTransfer = $this->setShipmentTypesToQuoteLevel($quoteTransfer, $availableShipmentTypes[$shipmentTypeKey] ?? null);
        $event->setData($quoteTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param \Symfony\Component\Form\FormInterface $form
     * @param \Symfony\Component\Form\FormEvent $event
     *
     * @return void
     */
    protected function hydrateShipmentTypeToItem(ItemTransfer $itemTransfer, FormInterface $form, FormEvent $event): void
    {
        if (!$this->addressFormChecker->isDeliverToMultipleAddresses($form)) {
            return;
        }

        $shipmentTypeKey = $form->get(ShipmentTypeAddressStepForm::FIELD_SHIPMENT_TYPE)
            ->get(ShipmentTypeSubForm::FIELD_SHIPMENT_TYPE_KEY)
            ->getData();

        $itemTransfer->setShipmentType($shipmentTypeKey ? (new ShipmentTypeTransfer())->setKey($shipmentTypeKey) : null);
        $event->setData($itemTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\ShipmentTypeTransfer|null $shipmentTypeTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function setShipmentTypesToQuoteLevel(
        QuoteTransfer $quoteTransfer,
        ?ShipmentTypeTransfer $shipmentTypeTransfer
    ): QuoteTransfer {
        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            $itemTransfer->setShipmentType($shipmentTypeTransfer);
        }

        return $quoteTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param array<string, \Generated\Shared\Transfer\ShipmentTypeTransfer> $shipmentTypeTransfers
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function setShipmentTypesToItemLevel(QuoteTransfer $quoteTransfer, array $shipmentTypeTransfers): QuoteTransfer
    {
        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            if (!$itemTransfer->getShipmentType()) {
                continue;
            }

            $shipmentTypeTransfer = $shipmentTypeTransfers[$itemTransfer->getShipmentTypeOrFail()->getKeyOrFail()] ?? null;
            $itemTransfer->setShipmentType($shipmentTypeTransfer);
        }

        return $quoteTransfer;
    }
}
