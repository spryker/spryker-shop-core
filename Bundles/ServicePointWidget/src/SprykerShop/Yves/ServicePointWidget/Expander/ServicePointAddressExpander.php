<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ServicePointWidget\Expander;

use Generated\Shared\Transfer\AddressTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\ServicePointTransfer;
use Spryker\Shared\Kernel\Transfer\AbstractTransfer;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

class ServicePointAddressExpander implements ServicePointAddressExpanderInterface
{
    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return \Symfony\Component\Form\FormBuilderInterface
     */
    public function expandShipmentsWithServicePointAddress(FormBuilderInterface $builder): FormBuilderInterface
    {
        $builder->addEventListener(FormEvents::POST_SUBMIT, function (FormEvent $event): void {
            $this->hydrateServicePointAddressToShipmentDeliveryAddress($event);
        });

        return $builder;
    }

    /**
     * @param \Symfony\Component\Form\FormEvent $event
     *
     * @return void
     */
    protected function hydrateServicePointAddressToShipmentDeliveryAddress(FormEvent $event): void
    {
        /** @var \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer */
        $quoteTransfer = $event->getData();

        if (!$this->isApplicable($quoteTransfer)) {
            return;
        }

        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            if (!$itemTransfer->getShipment() || !$itemTransfer->getServicePoint()) {
                continue;
            }

            $addressTransfer = $this->createAddressTransfer(
                $quoteTransfer->getCustomerOrFail(),
                $itemTransfer->getServicePointOrFail(),
            );

            $itemTransfer->getShipmentOrFail()->setShippingAddress($addressTransfer);
        }

        $event->setData($quoteTransfer);
    }

    /**
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer|\Generated\Shared\Transfer\QuoteTransfer $abstractTransfer
     *
     * @return bool
     */
    protected function isApplicable(AbstractTransfer $abstractTransfer): bool
    {
        return $abstractTransfer instanceof QuoteTransfer && $abstractTransfer->getCustomer();
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     * @param \Generated\Shared\Transfer\ServicePointTransfer $servicePointTransfer
     *
     * @return \Generated\Shared\Transfer\AddressTransfer
     */
    protected function createAddressTransfer(
        CustomerTransfer $customerTransfer,
        ServicePointTransfer $servicePointTransfer
    ): AddressTransfer {
        $servicePointAddressTransfer = $servicePointTransfer->getAddressOrFail();

        return (new AddressTransfer())
            ->fromArray($servicePointAddressTransfer->toArray(), true)
            ->setIso2Code($servicePointAddressTransfer->getCountryOrFail()->getIso2CodeOrFail())
            ->setFirstName($customerTransfer->getFirstName())
            ->setLastName($customerTransfer->getLastName())
            ->setSalutation($customerTransfer->getSalutation())
            ->setIsAddressSavingSkipped(true);
    }
}
