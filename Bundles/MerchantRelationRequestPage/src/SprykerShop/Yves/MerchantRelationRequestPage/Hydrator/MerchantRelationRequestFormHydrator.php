<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\MerchantRelationRequestPage\Hydrator;

use ArrayObject;
use Generated\Shared\Transfer\MerchantTransfer;
use SprykerShop\Yves\MerchantRelationRequestPage\Form\MerchantRelationRequestForm;
use Symfony\Component\Form\FormEvent;

class MerchantRelationRequestFormHydrator implements MerchantRelationRequestFormHydratorInterface
{
    /**
     * @param \Symfony\Component\Form\FormEvent $event
     * @param array<string, mixed> $options
     *
     * @return void
     */
    public function hydrateMerchant(FormEvent $event, array $options): void
    {
        /** @var \Generated\Shared\Transfer\MerchantRelationRequestTransfer $data */
        $data = $event->getData();
        $merchantReference = $data->getMerchantOrFail()->getMerchantReference();

        if (!$merchantReference) {
            return;
        }

        $merchantSearchTransfer = $options[MerchantRelationRequestForm::OPTION_MERCHANTS][$merchantReference] ?? null;
        if (!$merchantSearchTransfer) {
            return;
        }

        $merchantTransfer = (new MerchantTransfer())->fromArray($merchantSearchTransfer->toArray(), true);
        $data->setMerchant($merchantTransfer);
        $event->setData($data);
    }

    /**
     * @param \Symfony\Component\Form\FormEvent $event
     * @param array<string, mixed> $options
     *
     * @return void
     */
    public function hydrateOwnerCompanyBusinessUnit(FormEvent $event, array $options): void
    {
        /** @var \Generated\Shared\Transfer\MerchantRelationRequestTransfer $data */
        $data = $event->getData();
        $idBusinessUnit = $data->getOwnerCompanyBusinessUnitOrFail()->getIdCompanyBusinessUnit();

        if (!$idBusinessUnit) {
            return;
        }

        $hydratedBusinessUnit = $options[MerchantRelationRequestForm::OPTION_BUSINESS_UNITS][$idBusinessUnit] ?? null;
        if (!$hydratedBusinessUnit) {
            return;
        }

        $data->setOwnerCompanyBusinessUnit($hydratedBusinessUnit);
        $event->setData($data);
    }

    /**
     * @param \Symfony\Component\Form\FormEvent $event
     * @param array<string, mixed> $options
     *
     * @return void
     */
    public function hydrateAssigneeCompanyBusinessUnits(FormEvent $event, array $options): void
    {
        /** @var \Generated\Shared\Transfer\MerchantRelationRequestTransfer $data */
        $data = $event->getData();

        $hydratedAssigneeCompanyBusinessUnits = [];
        foreach ($data->getAssigneeCompanyBusinessUnits() as $companyBusinessUnitTransfer) {
            $idCompanyBusinessUnit = $companyBusinessUnitTransfer->getIdCompanyBusinessUnit();
            if (!$idCompanyBusinessUnit) {
                continue;
            }

            $hydratedBusinessUnit = $options[MerchantRelationRequestForm::OPTION_BUSINESS_UNITS][$idCompanyBusinessUnit] ?? null;
            if (!$hydratedBusinessUnit) {
                continue;
            }

            $hydratedAssigneeCompanyBusinessUnits[] = $hydratedBusinessUnit;
        }

        $data->setAssigneeCompanyBusinessUnits(new ArrayObject($hydratedAssigneeCompanyBusinessUnits));
        $event->setData($data);
    }
}
