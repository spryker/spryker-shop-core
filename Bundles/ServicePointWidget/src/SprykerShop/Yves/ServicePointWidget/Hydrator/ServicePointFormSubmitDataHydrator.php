<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ServicePointWidget\Hydrator;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\ServicePointTransfer;
use SprykerShop\Yves\ServicePointWidget\Checker\AddressFormCheckerInterface;
use SprykerShop\Yves\ServicePointWidget\Form\ServicePointAddressStepForm;
use SprykerShop\Yves\ServicePointWidget\Form\ServicePointSubForm;
use SprykerShop\Yves\ServicePointWidget\Reader\AvailableServicePointReaderInterface;
use SprykerShop\Yves\ServicePointWidget\Validator\ServicePointFormValidatorInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormInterface;

class ServicePointFormSubmitDataHydrator implements ServicePointFormSubmitDataHydratorInterface
{
    /**
     * @var \SprykerShop\Yves\ServicePointWidget\Reader\AvailableServicePointReaderInterface
     */
    protected AvailableServicePointReaderInterface $availableServicePointReader;

    /**
     * @var \SprykerShop\Yves\ServicePointWidget\Validator\ServicePointFormValidatorInterface
     */
    protected ServicePointFormValidatorInterface $servicePointFormValidator;

    /**
     * @var \SprykerShop\Yves\ServicePointWidget\Checker\AddressFormCheckerInterface
     */
    protected AddressFormCheckerInterface $addressFormChecker;

    /**
     * @param \SprykerShop\Yves\ServicePointWidget\Reader\AvailableServicePointReaderInterface $availableServicePointReader
     * @param \SprykerShop\Yves\ServicePointWidget\Validator\ServicePointFormValidatorInterface $servicePointFormValidator
     * @param \SprykerShop\Yves\ServicePointWidget\Checker\AddressFormCheckerInterface $addressFormChecker
     */
    public function __construct(
        AvailableServicePointReaderInterface $availableServicePointReader,
        ServicePointFormValidatorInterface $servicePointFormValidator,
        AddressFormCheckerInterface $addressFormChecker
    ) {
        $this->availableServicePointReader = $availableServicePointReader;
        $this->servicePointFormValidator = $servicePointFormValidator;
        $this->addressFormChecker = $addressFormChecker;
    }

    /**
     * @param \Symfony\Component\Form\FormEvent $event
     *
     * @return void
     */
    public function hydrate(FormEvent $event): void
    {
        /** @var \Generated\Shared\Transfer\QuoteTransfer|\Generated\Shared\Transfer\ItemTransfer|\Spryker\Shared\Kernel\Transfer\AbstractTransfer $data */
        $data = $event->getData();
        $form = $event->getForm();

        if (!$this->addressFormChecker->isApplicableForServicePointAddressStepFormHydration($data)) {
            return;
        }

        $this->servicePointFormValidator->validateSubmittedData($data, $form);

        if ($data instanceof QuoteTransfer) {
            $this->hydrateServicePointsToQuote($data, $form, $event);

            return;
        }

        /** @phpstan-var \Generated\Shared\Transfer\ItemTransfer $data */
        $this->hydrateServicePointToItem($data, $form, $event);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Symfony\Component\Form\FormInterface $form
     * @param \Symfony\Component\Form\FormEvent $event
     *
     * @return void
     */
    protected function hydrateServicePointsToQuote(QuoteTransfer $quoteTransfer, FormInterface $form, FormEvent $event): void
    {
        $servicePointTransfers = $this->availableServicePointReader->getServicePoints(
            $this->collectServicePointUuids($quoteTransfer, $form),
            $quoteTransfer->getStoreOrFail()->getNameOrFail(),
        );

        if ($this->addressFormChecker->isDeliverToMultipleAddresses($form)) {
            $quoteTransfer = $this->setServicePointsToItemLevel($quoteTransfer, $servicePointTransfers);
            $event->setData($quoteTransfer);

            return;
        }

        $servicePointUuid = $form->get(ServicePointAddressStepForm::FIELD_SERVICE_POINT)
            ->get(ServicePointSubForm::FIELD_SERVICE_POINT_UUID)
            ->getData();

        if ($this->addressFormChecker->isShipmentTypeDelivery($form)) {
            $servicePointUuid = null;
        }

        $quoteTransfer = $this->setServicePointsToQuoteLevel($quoteTransfer, $servicePointTransfers[$servicePointUuid] ?? null);
        $event->setData($quoteTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Symfony\Component\Form\FormInterface $form
     *
     * @return list<string>
     */
    protected function collectServicePointUuids(QuoteTransfer $quoteTransfer, FormInterface $form): array
    {
        $servicePointUuids = [];
        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            if ($itemTransfer->getServicePoint()) {
                $servicePointUuids[] = $itemTransfer->getServicePointOrFail()->getUuid();
            }
        }

        $servicePointUuids[] = $form->get(ServicePointAddressStepForm::FIELD_SERVICE_POINT)
            ->get(ServicePointSubForm::FIELD_SERVICE_POINT_UUID)
            ->getData();

        return array_filter(array_unique($servicePointUuids));
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param \Symfony\Component\Form\FormInterface $form
     * @param \Symfony\Component\Form\FormEvent $event
     *
     * @return void
     */
    protected function hydrateServicePointToItem(ItemTransfer $itemTransfer, FormInterface $form, FormEvent $event): void
    {
        if (!$this->addressFormChecker->isDeliverToMultipleAddresses($form)) {
            return;
        }

        $servicePointUuid = $form->get(ServicePointAddressStepForm::FIELD_SERVICE_POINT)
            ->get(ServicePointSubForm::FIELD_SERVICE_POINT_UUID)
            ->getData();

        if ($this->addressFormChecker->isShipmentTypeDelivery($form)) {
            $servicePointUuid = null;
        }

        $itemTransfer->setServicePoint($servicePointUuid ? (new ServicePointTransfer())->setUuid($servicePointUuid) : null);
        $event->setData($itemTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\ServicePointTransfer|null $servicePointTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function setServicePointsToQuoteLevel(
        QuoteTransfer $quoteTransfer,
        ?ServicePointTransfer $servicePointTransfer
    ): QuoteTransfer {
        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            $itemTransfer->setServicePoint($servicePointTransfer);
        }

        return $quoteTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param array<string, \Generated\Shared\Transfer\ServicePointTransfer> $servicePointTransfers
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function setServicePointsToItemLevel(QuoteTransfer $quoteTransfer, array $servicePointTransfers): QuoteTransfer
    {
        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            if (!$itemTransfer->getServicePoint()) {
                continue;
            }

            $servicePointTransfer = $servicePointTransfers[$itemTransfer->getServicePointOrFail()->getUuid()] ?? null;
            $itemTransfer->setServicePoint($servicePointTransfer);
        }

        return $quoteTransfer;
    }
}
