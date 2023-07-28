<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ServicePointWidget\Hydrator;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\ServicePointTransfer;
use Spryker\Shared\Kernel\Transfer\AbstractTransfer;
use SprykerShop\Yves\ServicePointWidget\Form\ServicePointAddressStepForm;
use SprykerShop\Yves\ServicePointWidget\Form\ServicePointSubForm;
use Symfony\Component\Form\FormEvent;

class ServicePointFormPreSetDataHydrator implements ServicePointFormPreSetDataHydratorInterface
{
    /**
     * @param \Symfony\Component\Form\FormEvent $event
     *
     * @return void
     */
    public function hydrate(FormEvent $event): void
    {
        /** @var \Generated\Shared\Transfer\QuoteTransfer|\Generated\Shared\Transfer\ItemTransfer|null $data */
        $data = $event->getData();
        $form = $event->getForm();

        if (!$this->isApplicable($data)) {
            return;
        }

        $form->add(ServicePointAddressStepForm::FIELD_SERVICE_POINT, ServicePointSubForm::class, [
            'required' => false,
            'mapped' => $data instanceof ItemTransfer,
            'label' => false,
            ServicePointSubForm::OPTION_SELECTED_SERVICE_POINT => $this->getSelectedServicePoint($data),
        ]);
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
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer|null $data
     *
     * @return \Generated\Shared\Transfer\ServicePointTransfer|null
     */
    protected function getSelectedServicePoint(?AbstractTransfer $data): ?ServicePointTransfer
    {
        if ($data instanceof QuoteTransfer && $this->isItemsHaveSameServicePoint($data)) {
            /** @var \Generated\Shared\Transfer\ItemTransfer $itemTransfer */
            $itemTransfer = $data->getItems()->getIterator()->current();

            return $itemTransfer->getServicePointOrFail();
        }

        if ($data instanceof ItemTransfer && $data->getServicePoint()) {
            return $data->getServicePointOrFail();
        }

        return null;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    protected function isItemsHaveSameServicePoint(QuoteTransfer $quoteTransfer): bool
    {
        $servicePointUuids = [];

        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            $servicePointUuid = $itemTransfer->getServicePoint() ? $itemTransfer->getServicePointOrFail()->getUuid() : null;

            if ($servicePointUuid) {
                $servicePointUuids[$servicePointUuid][] = $itemTransfer;
            }
        }

        return $quoteTransfer->getItems()->count()
            && count($servicePointUuids)
            && $quoteTransfer->getItems()->count() === count(reset($servicePointUuids));
    }
}
