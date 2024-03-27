<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\MerchantRelationRequestPage\Hydrator;

use Symfony\Component\Form\FormEvent;

interface MerchantRelationRequestFormHydratorInterface
{
    /**
     * @param \Symfony\Component\Form\FormEvent $event
     * @param array<string, mixed> $options
     *
     * @return void
     */
    public function hydrateMerchant(FormEvent $event, array $options): void;

    /**
     * @param \Symfony\Component\Form\FormEvent $event
     * @param array<string, mixed> $options
     *
     * @return void
     */
    public function hydrateOwnerCompanyBusinessUnit(FormEvent $event, array $options): void;

    /**
     * @param \Symfony\Component\Form\FormEvent $event
     * @param array<string, mixed> $options
     *
     * @return void
     */
    public function hydrateAssigneeCompanyBusinessUnits(FormEvent $event, array $options): void;
}
